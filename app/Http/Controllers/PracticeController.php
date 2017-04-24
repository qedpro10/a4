<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PracticeController extends Controller
{

    /**
    * Lecture 11) Get all books
    */
    public function practice7() {

    }
    /**
    * Lecture 11) Create a new book
    */
    public function practice6() {

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
