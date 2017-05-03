<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class WelcomeController extends Controller
{

    /**
	* GET
    * /
	*/
    public function __invoke() {

        if(Auth::user()) {
            return redirect('/main');
        }

        return view('welcome');
    }
}
