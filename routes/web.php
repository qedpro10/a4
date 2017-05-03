<?php

/**
* Book related routes
*/
# The following routes require authorization
Route::group(['middleware' => 'auth'], function () {

    Route::get('/books', 'BookController@index');

    # Get route to show a form to create a new book
    Route::get('/books/new', 'BookController@createNewBook');

    # Post route to process the form to add a new book
    Route::post('/books/new', 'BookController@storeNewBook');

    # Get route to show a form to edit an existing book
    Route::get('/books/edit/{id}', 'BookController@edit');

    # Post route to process the form to save edits to a book
    Route::post('/books/edit', 'BookController@saveEdits');

    # Get route to confirm deletion of book
    Route::get('/books/delete/{id}', 'BookController@confirmDeletion');

    # Post route to actually destroy the book
    Route::post('/books/delete', 'BookController@delete');
});

# Get route to show an individual book
Route::get('/books/{id?}', 'BookController@show');

# Get route to a search page
Route::get('/search', 'BookController@search');

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
Route::any('/practice/{n?}', 'PracticeController@index');


/**
* Auth related routes
*/
Auth::routes();

Route::get('/home', 'BookController@index');

Route::get('/logout', function() {
    Auth::logout();
    dump("You've been logged out");
});


/**
* Main homepage visitors see when they visit just /
*/
Route::get('/', 'WelcomeController');



/*----------------------------------------------------
Testing/Debugging routes below here
-----------------------------------------------------*/
// Route::get('/debug', function() {
//
// 	echo '<pre>';
//
// 	echo '<h1>Environment</h1>';
// 	echo App::environment().'</h1>';
//
// 	echo '<h1>Debugging?</h1>';
// 	if(config('app.debug')) echo "Yes"; else echo "No";
//
// 	echo '<h1>Database Config</h1>';
//     echo 'DB defaultStringLength: '.Illuminate\Database\Schema\Builder::$defaultStringLength;
//     /*
//     The following commented out line will print your MySQL credentials.
//     Uncomment this line only if you're facing difficulties connecting to the database and you
//     need to confirm your credentials.
//     When you're done debugging, comment it back out so you don't accidentally leave it
//     running on your production server, making your credentials public.
//     */
// 	//print_r(config('database.connections.mysql'));
//
// 	echo '<h1>Test Database Connection</h1>';
// 	try {
// 		$results = DB::select('SHOW DATABASES;');
// 		echo '<strong style="background-color:green; padding:5px;">Connection confirmed</strong>';
// 		echo "<br><br>Your Databases:<br><br>";
// 		print_r($results);
// 	}
// 	catch (Exception $e) {
// 		echo '<strong style="background-color:crimson; padding:5px;">Caught exception: ', $e->getMessage(), "</strong>\n";
// 	}
//
// 	echo '</pre>';
//
// });


if(App::environment('local')) {

    Route::get('/drop', function() {

        DB::statement('DROP database stocker');
        DB::statement('CREATE database stocker');

        return 'Dropped stocker; created stocker.';
    });

};


Route::get('/show-login-status', function() {

    # You may access the authenticated user via the Auth facade
    $user = Auth::user();

    if($user)
        dump('You are logged in.', $user->toArray());
    else
        dump('You are not logged in.');

    return;
});

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
