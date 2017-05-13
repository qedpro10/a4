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
     * /
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
                    $stocks = Stock::where('ticker', 'LIKE', $searchTicker.'%')->get();
                }
                else {
                    $stocks = Stock::where('ticker', '=', $searchTicker)->get();
                    //dump('getting exact for ' . $searchTicker);
                    //dump($stocks);
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
    */
    public function show($id) {

        $stock = Stock::find($id);

        if(!$stock) {
            Session::flash('message', 'The stock you requested could not be found.');
            return redirect('/');
        }

        // get 30 days of historical data
        $startDate = Carbon::now()->subMonths(1);
        $endDate = Carbon::now();
        $histData = YahooClient::getHistoricalData($stock->ticker, $startDate, $endDate);

        $currentData = YahooClient::getCurrentData($stock->ticker);

        return view('stocks.show')->with([
            'stock' => $stock,
            'current' => $currentData,
            'histData' => json_encode($histData),
        ]);
    }


    /**
    * GET
    * /stocks/new
    * Display the form to add a new stock that was alraedy searched for on YAHOO
    * Display the data taht came back from that search.
    */
    public function createNewStock(Request $request) {

        $ticker = $request->ticker;
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
            //$newStock->logo = $request->logo;
            //$newStock->website = $request->website;


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
        ];

        $this->validate($request, [
            'ticker' => 'required|min:1|max:6|alpha',
            'company_name' => 'required',
            'logo' => 'url',
            'website' => 'required|url',
            'exchange_id' => 'not_in:0',
        ], $messages);

        // first verify that the stock is not already in the DB
        // if it is then just favorite it for this user
        // need to use the first() method, get does not allow you to use ->users() method
        $newStock = Stock::where('ticker', '=', $request->ticker)->first();

        if (is_null($newStock)) {
        // else verify that the stock exists by doing a query

            # Add new stock to database
            $stock = new Stock();
            $stock->ticker = $request->ticker;
            $stock->company_name = $request->company_name;
            $stock->logo = $request->logo;
            $stock->website = $request->website;
            $stock->exchange_id = $request->exchange_id;

            // sync the user in the stock_user table
            $user = $request->user();
            $stock->save();
            $stock->users()->sync($user);
            Session::flash('message', 'The stock '.$request->ticker.' was added.');
        }
        else {
            //StockController::favorite($request->ticker);


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

    /*
    public function favoriteStock(Request $request) {
        favorite($request->ticker);

    }

    public function favorite($ticker) {
        // favorite the stock - add ot the pivot table if its not already there
        // need to verify that user hasn't already been added because it
        // will add another entry to the pivot table.
        // first verify that the stock is not already in the DB
        // if it is then just favorite it for this user
        // need to use the first() method, get does not allow you to use ->users() method
        $stock = Stock::where('ticker', '=', ticker)->first();

        $user = Auth::user();
        $testUser = $stock->users()->where('user_id', '=', $user->user_id)->get();
        if ($testUser->isEmpty()) {
            $stock->users()->save($user);
            Session::flash('message', 'The stock '.$ticker.' has been added to favorites.');
        }
        else {
            Session::flash('message', 'The stock '.$ticker.' already a favorite.');
        }

    }

    */
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
            //'tagsForCheckboxes' => $tagsForCheckboxes,
            //'tagsForThisStock' => $tagsForThisStock,
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
        ];
        $this->validate($request, [
            'company_name' => 'required',
            'logo' => 'nullable|url',
            'website' => 'nullable|url',
            'exchange_id' => 'not_in:0',
        ], $messages);

        $stock = Stock::find($request->id);
        # Edit stock in the database
        $stock->company_name = $request->company_name;
        $stock->logo = $request->logo;
        $stock->website = $request->website;
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

    // test function
    public function googleLineChart(Request $request) {

        $startDate = Carbon::now()->subMonths(1);
        $endDate = Carbon::now();
        $data = YahooClient::getHistoricalData("CIEN", $startDate, $endDate);
        //echo json_encode($data);

        return view('stocks.chart')->with([
            'histData' => json_encode($data),
        ]);
    }




}
