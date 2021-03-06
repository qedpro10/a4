<?php

/**
* Book related routes
*/
# The following routes require authorization
Route::group(['middleware' => 'auth'], function () {

    Route::get('/stocks', 'StockController@index');

    # Get route to show a form to create new stock in portfolio
    Route::get('/stocks/newSearch', 'StockController@newSearch');

    # Get route to show a form to create new stock in portfolio
    Route::post('/stocks/new', 'StockController@createNewStock');

    # Post route to process the form to add a new stock
    Route::post('/stocks/add', 'StockController@storeNewStock');

    # Get route to show a form to edit an existing stock
    Route::get('/stocks/edit/{id}', 'StockController@edit');

    # Post route to process the form to save edits to the stock
    Route::post('/stocks/edit', 'StockController@saveEdits');

    # Get route to confirm deletion of stock
    Route::get('/stocks/delete/{id}', 'StockController@confirmDeletion');

    # Post route to remove stock from tracking portfolio
    Route::post('/stocks/delete', 'StockController@delete');

    # Get route to show a form to create new stock in portfolio
    #Route::get('/stocks/find', 'StockController@findStock');

    # Get route to a search page
    Route::get('/stocks/search', 'StockController@search');

    # Post info for search of database or YAHOO DB
    # redirects to the "Add To Portfolio page"
    Route::post('/stocks/search', 'StockController@find');

    # Get route to show an individual stock
    Route::get('/stocks/show/{id?}', 'StockController@show');

    # Get route to show a form to edit an existing stock
    //Route::get('/stocks/favorite/{id}', 'StockController@favorite');

});

# Get route to the about page accessible w/o logging in
Route::get('/about', 'StockController@about');

/**
* Log viewer
* (only accessible locally)
*/

if(config('app.env') == 'local') {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
}


/**
* Practice
*/
if(config('app.env') == 'local') {
    Route::any('/practice/{n?}', 'PracticeController@index');
}

/**
* Auth routes

*/
Auth::routes();

Route::get('/home', 'StockController@index');

Route::get('/logout', function() {
    Auth::logout();
    dump("You've been logged out");
});
/**
* Main homepage visitors see when they visit just /
*/
Route::get('/', 'WelcomeController');


if(App::environment('local')) {

    Route::get('/drop', function() {

        DB::statement('DROP database stocker');
        DB::statement('CREATE database stocker');

        return 'Dropped stocker; created stocker.';
    });

};

if(config('app.env') == 'local') {
    Route::get('/show-login-status', function() {

        # You may access the authenticated user via the Auth facade
        $user = Auth::user();

        if($user)
            dump('You are logged in.', $user->toArray());
        else
            dump('You are not logged in.');

        return;
    });
}

if(config('app.env') == 'local') {
    Route::get('/debug', function() {

    	echo '<pre>';

    	echo '<h1>Environment</h1>';
    	echo App::environment().'</h1>';

    	echo '<h1>Debugging?</h1>';
    	if(config('app.debug')) echo "Yes"; else echo "No";

    	echo '<h1>Database Config</h1>';
        	echo 'DB defaultStringLength: '.Illuminate\Database\Schema\Builder::$defaultStringLength;
        	/*
    	The following commented out line will print your MySQL credentials.
    	Uncomment this line only if you're facing difficulties connecting to the database and you
            need to confirm your credentials.
            When you're done debugging, comment it back out so you don't accidentally leave it
            running on your production server, making your credentials public.
            */
    	//print_r(config('database.connections.mysql'));

    	echo '<h1>Test Database Connection</h1>';
    	try {
    		$results = DB::select('SHOW DATABASES;');
    		echo '<strong style="background-color:green; padding:5px;">Connection confirmed</strong>';
    		echo "<br><br>Your Databases:<br><br>";
    		print_r($results);
    	}
    	catch (Exception $e) {
    		echo '<strong style="background-color:crimson; padding:5px;">Caught exception: ', $e->getMessage(), "</strong>\n";
    	}

    	echo '</pre>';

    });
}
