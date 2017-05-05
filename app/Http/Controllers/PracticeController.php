<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use Carbon\Carbon;
use App\YahooClient;

class PracticeController extends Controller
{
    public function practice10() {
        $data = [
          ['Mon', 20, 28, 38, 45],
          ['Tue', 31, 38, 55, 66],
          ['Wed', 50, 55, 77, 80],
          ['Thu', 77, 77, 66, 50],
          ['Fri', 68, 66, 22, 15]
          // Treat first row as data as well.
        ];
        dump($data);
    }
    /**
    * Update a stock
    */
    public function practice9() {

        $startDate = Carbon::createFromDate(2017, 5, 1, 'America/Toronto');
        $endDate = Carbon::createFromDate(2017, 5, 4, 'America/Toronto');
        $data = YahooClient::getHistoricalData("CIEN", $startDate, $endDate);
        dump($data);
        //Search stocks
        //$data = $client->search("Yahoo");
    }
    /**
    * Update a stock
    */
    public function practice8() {
        # First get the stock to update
        $stock = Stock::where('company_name', 'LIKE', '%Ciena%')->first();

        if(!$stock) {
            dump("Stock not found, can't update.");
        }
        else {

            # Change some properties
            $stock->dividend = true;
            $stock->website = 'http:/www.ciena.com';

            # Save the changes
            $stock->save();

            dump('Update complete; check the database to confirm the update worked.');
        }
    }
    /**
    * Get all stocks that match company name Ciena
    */
    public function practice7() {
        //$stock = new Stock();
        //$stocks = $stock->where('company_name', 'LIKE', '%CIENA%')->get();
        //or
        $stocks = Stock::where('company_name', 'LIKE', '%CIENA%')->get();

        if($stocks->isEmpty()) {
            dump('No matches found');
        }
        else {
            foreach($stocks as $stock) {
                dump($stock->ticker);
            }
        }
    }
    /**
    * Create a new stock
    */
    public function practice6() {
        # Instantiate a new Stock Model object
        $stock = new Stock();

        # Set the parameters
        # Note how each parameter corresponds to a field in the table
        $stock->ticker = "CIEN";
        $stock->company_name = 'Ciena';
        $stock->logo = "";
        $stock->website = 'http:/www.ciena.com/investors';
        $stock->dividend = false;

        # Invoke the Eloquent `save` method to generate a new row in the
        # `stocks` table, with the above data
        $stock->save();

        dump('Added: '.$stock->ticker);
    }
    /**
    * Example for Clayton
    */
    public function practice5() {
        echo $this->variableSetInController;
    }
    /**
    * https://github.com/susanBuck/dwa15-spring2017-notes/blob/master/03_Laravel/15_Composer_Packages.md
    */
    public function practice4() {
        # Method 1) No alias, no use statement
        #$random = new \Rych\Random\Random();
        # Method 2) Assuming `use Rych\Random\Random;` at the top
        #$random = new Random();
        # Method 3) When set as an alias in config/app.php
        $random = new \Random();
        return $random->getRandomString(8);
    }
    /**
    *
    */
    public function practice3() {
        $random = new \Random;
        // Generate a 16-byte string of random raw data
        $randomBytes = $random->getRandomBytes(16);
        dump($randomBytes);
        // Get a random integer between 1 and 100
        $randomNumber = $random->getRandomInteger(1, 100);
        dump($randomNumber);
        // Get a random 8-character string using the
        // character set A-Za-z0-9./
        $randomString = $random->getRandomString(8);
        dump($randomString);
    }
    /**
    *
    */
    public function practice2() {
        dump(config('app'));
    }
    /**
    *
    */
    public function practice1() {
        dump('This is the first example.');
    }
    /**
    * ANY (GET/POST/PUT/DELETE)
    * /practice/{n?}
    *
    * This method accepts all requests to /practice/ and
    * invokes the appropriate method.
    *
    * http://foobooks.loc/practice/1 => Invokes practice1
    * http://foobooks.loc/practice/5 => Invokes practice5
    * http://foobooks.loc/practice/999 => Practice route [practice999] not defined
    */
    public function index($n = null) {
        # If no specific practice is specified, show index of all available methods
        if(is_null($n)) {
            foreach(get_class_methods($this) as $method) {
                if(strstr($method, 'practice'))
                echo "<a href='".str_replace('practice','/practice/',$method)."'>" . $method . "</a><br>";
            }
        }
        # Otherwise, load the requested method
        else {
            $method = 'practice'.$n;
            if(method_exists($this, $method))
            return $this->$method();
            else
            dd("Practice route [{$n}] not defined");
        }
    }
}
