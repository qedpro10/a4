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
    * /main - main Stocker page -
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
            // get the 3 most recently added and show them
            $newStocks = $stocks->sortByDesc('created_at')->take(3);
        }

        return view('stocks.index')->with([
            'stocks' => $stocks,
            'newStocks' => $newStocks,
        ]);
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
     * /search
    */
    public function search(Request $request) {
        $searchResults = [];
        $searchTicker = $request->input('ticker', null);

        // only search if the ticker has been set
        if($searchTicker) {
            // Get search type
            $searchType = $request->input('searchType');

            if ($searchType == 'stockEx') {
                dump("searching exchanges for " . $searchTicker);
                // search the stock exchange for the ticker
                $data = YahooClient::findStock(strToUpper($searchTicker));
                if (is_null($data)) {
                    // stock was not found
                    // generate an error message and redirect back to the same page
                }
                else {
                    
                    // get the stock info to display
                    // add to the search results
                    //$stock = [$data->

                    //];
                }
            }
            else {
                // search the local database
                // create the search ticker based on exact or not
                // note that this should be applicable to both local and stocks
                // exchange searches but the stock API doesn't work for general
                // searches
                $exact = $request->input('exact');
                if (!$exact) {
                    // search the database with fuzzy search assuming that the first
                    // letters specified are a match but the rest are not
                    $searchTicker = $searchTicker . '%';
                }

                $stocks = Stock::where('ticker', 'LIKE', $searchTicker)->get();
                //dump($stocks);
                foreach($stocks as $stock) {
                    $searchResults[$stock->ticker] = $stock;
                }
                //dd($searchResults);
            }
        }

        return view('stocks.search')->with([
            'searchTicker' => $searchTicker,
            'exact' => $request->has('exact'),
            'searchResults' => $searchResults,
        ]);
    }

    /**
    * GET
    * /search - old code - not sure how this fits in
    */
    public function searchDB(Request $request) {
        # Start with an empty array of search results; stocks that
        # match our search query will get added to this array
        $searchResults = [];
        # Store the searchTerm in a variable for easy access
        # The second parameter (null) is what the variable
        # will be set to *if* searchTerm is not in the request.
        $searchTerm = $request->input('searchTerm', null);
        # Only try and search *if* there's a searchTerm
        if($searchTerm) {
            # Open the stocks.json data file
            $stocksRawData = file_get_contents(database_path().'/stocks.json');
            # Decode the stock JSON data into an array
            # Nothing fancy here; just a built in PHP method
            $stocks = json_decode($stocksRawData, true);

            # Loop through all the stock data, looking for matches
            foreach($stocks as $ticker => $stock) {
                # Case sensitive boolean check for a match
                if($request->has('caseInsensitive')) {
                    $match = $ticker == $searchTerm;
                }
                # If it was a match, add it to our results
                if($match) {
                    $searchResults[$ticker] = $stock;
                }
            }
        }
        # Return the view, with the searchTerm *and* searchResults (if any)
        return view('stocks.search')->with([
            'searchTerm' => $searchTerm,
            'caseSensitive' => $request->has('caseSensitive'),
            'searchResults' => $searchResults
        ]);
    }

    /**
    * GET
    * /stocks/new
    * Display the form to add a new stock
    */
    public function createNewStock(Request $request) {
        if(!Auth::check()) {
            Session::flash('message','You have to be logged in to create a new stock');
            return redirect('/');
        }
        $exchangesForDropdown = Exchange::getExchangesForDropdown();
        //$tagsForCheckboxes = Tag::getTagsForCheckboxes();
        return view('stocks.new')->with([
            'exchangesForDropdown' => $exchangesForDropdown,
            //'tagsForCheckboxes' => $tagsForCheckboxes
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
            'logo' => 'url',
            'website' => 'url',
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
        return redirect('/stocks/edit/'.$request->id);
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
