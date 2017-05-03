<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StockerController extends Controller
{
    //
    /**
	* GET
    * /main - main Stocker page -
	*/
    public function index(Request $request) {

        return view('stocker.main')->with([

        ]);
    }
}
