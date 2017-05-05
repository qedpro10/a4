<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use Session;
use Auth;
use Carbon\Carbon;
use App\YahooClient;
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


        $options = [
            'title' => 'Population of Largest U.S. Cities',
            'chartArea' => ['width' => '50%'],
            'hAxis' => [
                'title' => 'Total Population',
                'minValue' => 0
            ],
            'vAxis' => [
                'title' => 'City'
            ],
            'bars' => 'horizontal', //required if using material chart
            'axes' => [
                'y' => [0 => ['side' => 'right']]
            ]
        ];

        $cols = ['City', '2010 Population', '2000 PopulaÎtions'];
        $rows = [
            ['New York City, NY', 8175000, 8008000],
            ['Los Angeles, CA', 3792000, 3694000],
            ['Chicago, IL', 2695000, 2896000],
            ['Houston, TX', 2099000, 1953000],
            ['Philadelphia, PA', 1526000, 1517000]
        ];


        $stock = Stock::find($id);

        if(!$stock) {
            Session::flash('message', 'The stock you requested could not be found.');
            return redirect('/');
        }

        // get 30 days of data
        $startDate = Carbon::now()->subMonths(1);
        $endDate = Carbon::now();
        $historicalData = YahooClient::getHistoricalData($stock->ticker, $startDate, $endDate);

        $currentData = YahooClient::getCurrentData($stock->ticker);
        //dump($currentData);

        $open = $currentData['Open'];
        //dump($open);
        //dump($currentData);

        return view('stocks.show')->with([
            'stock' => $stock,
            'current' => $currentData,
            //'historical' => $historicalData,
            'options' => $options,
            'cols' => $cols,
            'rows' => $rows,
        ]);
    }

    /**
    * GET
    * /search
    */
    public function search(Request $request) {
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
        $exchangesForDropdown = Exchanges::getExchangesForDropdown();
        $tagsForCheckboxes = Tag::getTagsForCheckboxes();
        return view('stocks.new')->with([
            'exchangesForDropdown' => $exchangesForDropdown,
            'tagsForCheckboxes' => $tagsForCheckboxes
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
            'ticker' => 'required|min:3',
            'company_name' => 'required|alphanumeric',
            'logo' => 'url',
            'website' => 'required|url',
            'exchange_id' => 'not_in:0',
        ], $messages);

        # Add new stock to database
        $stock = new Stock();
        $stock->ticker = $request->ticker;
        $stock->company_name = $request->company_name;
        $stock->logo = $request->logo;
        $stock->website = $request->website;
        $stock->exchange_id = $request->exchange_id;
        //$stock->user_id = $request->user()->id;
        $stock->save();
        # Now handle tags.
        # Note how the stock has to be created (save) first *before* tags can
        # be added; this is because the tags need a stock_id to associate with
        # and we don't have a stock_id until the stock is created.
        //$tags = ($request->tags) ?: [];
        //$stock->tags()->sync($tags);
        $stock->save();
        Session::flash('message', 'The stock '.$request->ticker.' was added.');
        # Redirect the user to stock index
        return redirect('/stocks');
    }

    /**
    * GET
    * /stocks/edit/{id}
    * Show form to edit a stock
    */
    public function edit($id) {
        $stock = Stock::with('tags')->find($id);
        if(is_null($stock)) {
            Session::flash('message', 'The stock you requested was not found.');
            return redirect('/stocks');
        }
        $exchangesForDropdown = Exchange::getExchangesForDropdown();
        //$tagsForCheckboxes = Tag::getTagsForCheckboxes();
        # Create a simple array of just the tag names for tags associated with this stock;
        # will be used in the view to decide which tags should be checked off
        //$tagsForThisStock = [];
        //foreach($stock->tags as $tag) {
        //    $tagsForThisStock[] = $tag->name;
        //}
        # Results in an array like this: $tagsForThisStock => ['novel','fiction','classic'];
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
            'ticker' => 'required|min:3',
            'company_name' => 'required|alphanumeric',
            'logo' => 'url',
            'website' => 'url',
            'exchange_id' => 'not_in:0',
        ], $messages);

        $stock = Stock::find($request->id);
        # Edit stock in the database
        $stock->ticker = $request->ticker;
        $stock->company_name = $request->company_name;
        $stock->logo = $request->logo;
        $stock->website = $request->website;
        $stock->exchange_id = $request->exchange_id;
        # If there were tags selected...
        if($request->tags) {
            $tags = $request->tags;
        }
        # If there were no tags selected (i.e. no tags in the request)
        # default to an empty array of tags
        else {
            $tags = [];
        }
        # Above if/else could be condensed down to this: $tags = ($request->tags) ?: [];
        # Sync tags
        $stock->tags()->sync($tags);
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
        if(!$stock) {
            Session::flash('message', 'Deletion failed; stock not found.');
            return redirect('/stocks');
        }
        $stock->tags()->detach();
        $stock->delete();
        # Finish
        Session::flash('message', $stock->ticker.' was deleted.');
        return redirect('/stocks');
    }

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
