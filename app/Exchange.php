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

        # Retrieve the exchanges
        $exchanges = Exchange::orderBy('exchange_short', 'ASC')->get();

        # Organize the exchanges into an array where the key = exchange id and value = exchange name
        $exchangesForDropdown = [];
        foreach($exchanges as $exchange) {
            $exchangesForDropdown[$exchange->id] = $exchange->exchange_short;
        }

        return $exchangesForDropdown;
    }

    // helper function that converts the Yahoo Stock Exchange into the exchange id
    // needed by the one to many exchange->stock table
    public static function getExchangeId($yahooEx) {
        switch ($yahooEx) {
            case 'NYQ':
                $id = 1;
                break;
            case 'NMS':
                $id = 2;
                break;
            case 'TSE':
                $id = 3;
                break;
            // catch all for foreign traded in the nyse mkt
            case 'NYSE_MKT':
            default:
                $id = 4;
                break;
        }
        return $id;
    }

    public function convertExchangeName($yahooEx) {
        switch ($yahooEx) {
            case 'NYQ':
                $name='NYSE';
                break;
            case 'NMS':
                $name='NASDAQ';
                break;
            case 'TSE':
                $name='TSE';
            default:
                $name = "NYSE_MKT";
        }
        return $name;
    }

}
