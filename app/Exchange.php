<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    public function stocks() {
		# Exchange has many stocks
		# Define a one-to-many relationship.
		return $this->hasMany('App\Stock');
	}

    public static function getExchangesForDropdown() {

        # Get all the authors
        $exchanges = Exchange::orderBy('exchange_short', 'ASC')->get();

        # Organize the authors into an array where the key = author id and value = author name
        $exchangesForDropdown = [];
        foreach($exchanges as $exchange) {
            $exchangesForDropdown[$exchange->id] = $exchange->exchange_short;
        }

        return $exchangesForDropdown;
    }
}
