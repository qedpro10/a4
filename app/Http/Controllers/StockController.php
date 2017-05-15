<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Exchange;
use Session;
use Auth;
use Carbon\Carbon;
use App\YahooClient;
use App\User;
use DB;

class StockController extends Controller
{
    //
    /**
	* GET
    * /index - main Stocker page - shows the stocks that the user
    *          has added to their portfolio
    *          Can only get to this page via a login
    *          Shows the users stocks and the last one added
	*/
    public function index(Request $request) {

        // return null values if a user is not logged in
        $stocks = [];
        $newStocks = [];

        // $user = $request->user();
        $user = Auth::user();
        if($user) {
            // use the pivot table to get all the stocks favorited by the user
            $stocks = $user->stocks()->orderBy('ticker')->get();
            // get the most recently added and list it
            $newStocks = $user->stocks()->orderBy('created_at', 'desc')->take(1);
            if (is_null($newStocks)) {
                dd("No new stock");
            }
        }

        return view('stocks.index')->with([
            'stocks' => $stocks,
            'newStocks' => $newStocks,
        ]);
    }

    /**
    * GET
    * /about - Stocker information page - describes what Stocker is and does
    *          Gives an explanation of Bullish Engulfing Pattern
    */
    public function about(Request $request) {
        return view('stocks.about');
    }

    /**
    * GET
    * /search - Stocker search page - allows user to do internal or external
    *           search based on either an exact or fuzzy match.
    */
    public function search(Request $request) {
        return view('stocks.search')->with([
            'stocks' => null,
            'searchType' => null,
            'exactMatch' => null,
            'searchType' => null,
        ]);
    }

    /**
     * GET
     * This function does a search for a stock.  if the search is
     * external - that is - requested to serach stock exchange, then
     * the request goes thru the YAHOO Api and returns the stock matching the
     * specified symbol.  YAHOO doesn't provide fuzzy searching so its always an
     * exact match.  If local is selected then it will search the users database
     * for any stocks matching the criteria.  Exact match search and fuzzy search
     * both work.
    */
    public function find(Request $request) {

        # Custom error message
        $messages = [
            'exchange_id.not_in' => 'Ticker not specified.',
        ];

        $this->validate($request, [
            'ticker' => 'required|min:1|max:6|alpha',
        ], $messages);

        $searchResults = [];

        // get the info needed for the non-search view
        $searchTicker = $request->input('ticker', null);
        $searchType = $request->input('searchType', null);
        $exactMatch = $request->has('exactMatch');

        // only search if the ticker has been set
        if($searchTicker) {

            if ($searchType == 'stockEx') {
                //dd("searching exchanges for " . $searchTicker);

                try {
                    // search the stock exchange for the ticker
                    $data = YahooClient::findStock(strToUpper($searchTicker));
                    // check to see if Yahoo found the stock
                    // if not return to page with error message
                    if (is_null($data)) {
                        Session::flash('message','Stock  '.$searchTicker . ' not found');
                        return view('stocks.search')->with([
                            'searchTicker' => $searchTicker,
                            'exactMatch' => $exactMatch,
                            'searchType' => $searchType,
                            'stocks' => null,
                        ]);
                    }
                    // else get the stock 30 day closing data to display
                    $startDate = Carbon::now()->subMonths(3);
                    $endDate = Carbon::now();
                    $histData = YahooClient::getMovingData($searchTicker, $startDate, $endDate);
                }
                catch (ApiException $e) {
                    // Yahoo request returned exception
                    // return to page with an error
                    Session::flash('message','Error contacting Yahoo - try again later');
                    return view('stocks.search')->with([
                        'searchTicker' => $searchTicker,
                        'exactMatch' => $exactMatch,
                        'searchType' => $searchType,
                    ]);

                }

                Session::forget('message');
                return view('stocks.searchResults')->with([
                    'searchTicker' => $searchTicker,
                    'current'=> $data,
                    'histData' => json_encode($histData),
                ]);
            }
            else {
                // search the local database
                // create the search ticker based on exact or not
                // note that this should be applicable to both local and stocks
                // exchange searches but the stock API doesnt work for general
                // searches
                if (!$exactMatch) {
                    // search the database with fuzzy search assuming that the first
                    // letters specified are a match but the rest are not
                    $user = Auth::user();

                    // looking at the database for stocks that the user has added only!
                    $stocks = $user->stocks()->where('ticker', 'LIKE', $searchTicker.'%')->get();
                }
                else {
                    $stocks = Stock::where('ticker', '=', $searchTicker)->get();
                    //dump('getting exact for ' . $searchTicker);
                    //dump($stocks);
                }

                if (count($stocks) == 0) {
                    Session::flash('message', 'Stock not found - try searching exchange.');
                }

                return view('stocks.search')->with([
                    'searchTicker' => $searchTicker,
                    'searchType' => $searchType,
                    'exactMatch' => $exactMatch,
                    'stocks' => $stocks,
                ]);
            }
        }
    }

    /**
    * GET
    * /stocks/{id}
    * This collects the data to produce the BEP chart and then directs to the
    * show view
    */
    public function show($id) {

        // get the stock from the database
        $stock = Stock::find($id);

        if(!$stock) {
            Session::flash('message', 'The stock you requested could not be found.');
            return redirect('/');
        }

        // get 30 days of historical data and the current data
        $startDate = Carbon::now()->subMonths(1);
        $endDate = Carbon::now();
        $histData = YahooClient::getHistoricalData($stock->ticker, $startDate, $endDate);

        $currentData = YahooClient::getCurrentData($stock->ticker);

        // Sometimes the YAHOO API returns with an error.  This is caught in a
        // try/catch but need to handle the return of null data.
        // This issue is difficult to reproduce but i think this solves it
        if (is_null($histData) || is_null($currentData)) {
            Session::flash('message', 'Unable to contact YAHOO - try again later.');
            $recommendation = "WAIT";
        }
        else {
            // makes recommendation based on the current and historical data
            $recommendation = YahooClient::analyze($currentData, $histData);
        }

        // return the show view with the candlestick graph and recommendation
        return view('stocks.show')->with([
            'stock' => $stock,
            'current' => $currentData,
            'histData' => json_encode($histData),
            'recommendation' => $recommendation,
        ]);
    }

    /**
    * GET
    * /stocks/new
    * Display the form to add a new stock that was alraedy searched for on YAHOO
    * Display the data that came back from that search.
    */
    public function createNewStock(Request $request) {

        // get the stock information from what was returned from YAHOO search
        $ticker = $request->ticker;
        // convert the exchange into a known entity
        $exchange_id = Exchange::getExchangeId($request->exchange);
        $company_name = $request->company_name;
        $user = $request->user();

        // add stock to database only if it is not present
        $stock = Stock::where('ticker', '=', $ticker)->first();

        if (is_null($stock)) {

            $newStock = new Stock();
            $newStock->ticker = $ticker;
            $newStock->company_name = $company_name;
            $newStock->exchange_id = $exchange_id;

            // sync the user in the stock_user table
            $newStock->save();
            $newStock->users()->sync($user);
            Session::flash('message', 'The stock '.$ticker.' was added.');
        }
        else {
            // favorite the stock
            $stock->users()->sync($user);
            Session::flash('message', 'The stock '.$ticker.' was added to favorites.');
        }

        // get the info needed to return to the home page
        // return null values if a user is not logged in
        $stocks = [];
        $newStocks = [];

        // $user = $request->user();
        $user = Auth::user();
        if($user) {
            // use the pivot table to get all the stocks favorited by the user
            $stocks = $user->stocks()->orderBy('ticker')->get();
            // get the most recently added and list it
            $newStocks = $stocks->sortByDesc('created_at')->take(1);
        }
        // clear the message - because sometimes it doesn't clear
        Session::forget('message');
        return view('stocks.index')->with([
            'stocks' => $stocks,
            'newStocks' => $newStocks,
        ]);
    }

    /**
    * POST
    * /stocks/new
    * Process the form for adding a new stock
    */
    public function storeNewStock(Request $request) {
        # Custom error message
        $messages = [
            'exchange_id.not_in' => 'Exchange not selected.',
            'dividend.not_in' => 'Dividend not selected.',
        ];

        $this->validate($request, [
            'ticker' => 'required|min:1|max:6|alpha',
            'company_name' => 'required',
            'logo' => 'url',
            'website' => 'required|url',
            'exchange_id' => 'not_in:0',
            'dividend' => 'date',
        ], $messages);

        // first verify that the stock is not already in the DB
        // if it is then just favorite it for this user
        // need to use the first() method, get does not allow you to use ->users() method
        $newStock = Stock::where('ticker', '=', $request->ticker)->first();

        if (is_null($newStock)) {
            // stock is not in database so add it
            # Add new stock to database
            $stock = new Stock();
            $stock->ticker = $request->ticker;
            $stock->company_name = $request->company_name;
            $stock->logo = $request->logo;
            $stock->website = $request->website;

            // set the dividend based on the DividendPayDate not being ''
            $stock->dividend = 'none';
            if ($request->dividend != '') {
                $stock->dividend = 'quarterly';
            }

            $stock->exchange_id = $request->exchange_id;

            // sync the user in the stock_user table
            $user = $request->user();
            $stock->save();
            $stock->users()->sync($user);
            Session::flash('message', 'The stock '.$request->ticker.' was added.');
        }
        else {
            // favorite the stock - add ot the pivot table if its not already there
            // need to verify that user hasn't already been added because it
            // will add another entry to the pivot table.
            $user = $request->user();
            $testUser = $newStock->users()->where('user_id', '=', $user->user_id)->get();
            if ($testUser->isEmpty()) {
                $newStock->users()->save($user);
                Session::flash('message', 'The stock '.$request->ticker.' has been added to favorites.');
            }
            else {
                Session::flash('message', 'The stock '.$request->ticker.' already a favorite.');
            }


        }
        # Redirect the user to stock index
        return redirect('/stocks');
    }

    /**
    * GET
    * /stocks/edit/{id}
    * Show form to edit a stock
    */
    public function edit($id) {
        $stock = Stock::find($id);
        if(is_null($stock)) {
            Session::flash('message', 'The stock you requested was not found.');
            return redirect('/stocks');
        }

        $exchangesForDropdown = Exchange::getExchangesForDropdown();

        return view('stocks.edit')->with([
            'id' => $id,
            'stock' => $stock,
            'exchangesForDropdown' => $exchangesForDropdown,
        ]);
    }
    /**
    * POST
    * /stocks/edit
    * Process form to save edits to a stock
    */
    public function saveEdits(Request $request) {
        # Custom error message
        $messages = [
            'exchange_id.not_in' => 'Exchange not selected.',
            'dividend.not_in' => 'Dividend not selected.',
        ];
        $this->validate($request, [
            'company_name' => 'required',
            'logo' => 'nullable|url',
            'website' => 'nullable|url',
            'exchange_id' => 'not_in:0',
            'dividend' => 'not_in:0',
        ], $messages);

        // find the stock in the db
        $stock = Stock::find($request->id);
        # Edit stock in the database
        $stock->company_name = $request->company_name;
        $stock->logo = $request->logo;
        $stock->website = $request->website;
        $stock->dividend = $request->dividend;
        $stock->exchange_id = $request->exchange_id;

        $stock->save();
        Session::flash('message', 'Your changes to '.$stock->ticker.' were saved.');
        return redirect('/stocks');
    }
    /**
    * GET
    * Page to confirm deletion
    */
    public function confirmDeletion($id) {
        # Get the stock they're attempting to delete
        $stock = Stock::find($id);
        if(!$stock) {
            Session::flash('message', 'Stock not found.');
            return redirect('/stocks');
        }
        return view('stocks.delete')->with('stock', $stock);
    }
    /**
    * POST
    * Actually delete the stock
    */
    public function delete(Request $request) {
        # Get the stock to be deleted
        $stock = Stock::find($request->id);
        if(is_null($stock)) {
            Session::flash('message', 'Deletion failed; stock not found.');
            return redirect('/stocks');
        }
        // get the user
        $user = $request->user();
        //only delete the pivot entry associated with that user
        $stock->users()->detach($user->id);

        // check to see if there are any other users attached to this Stock
        // if not delete the stock from the table permanently
        $testUser = $stock->users()->first();

        if (is_null($testUser)) {
            $stock->delete();
            Session::flash('message', $stock->ticker.' was deleted.');
        }
        else {
            Session::flash('message', $stock->ticker.' was removed from favorites.');
        }
        # Finish
        return redirect('/stocks');
    }
}
