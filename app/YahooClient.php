<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class YahooClient
{
    public static function getHistoricalData($ticker, $startDate, $endDate) {

        $client = new \Scheb\YahooFinanceApi\ApiClient();
        $data = $client->getHistoricalData($ticker, $startDate, $endDate);
        return $data;
    }

    public static function getCurrentData($ticker) {

        $client = new \Scheb\YahooFinanceApi\ApiClient();
        $currentData = $client->getQuotes($ticker);
        $query = $currentData['query'];
        $results = $query['results'];
        $quote = $results['quote'];
        return $quote;
    }
}
