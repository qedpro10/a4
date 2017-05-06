<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class YahooClient
{
    // Gets the stock historical data for the given date range
    public static function getHistoricalData($ticker, $startDate, $endDate) {

        $client = new \Scheb\YahooFinanceApi\ApiClient();
        $hist = $client->getHistoricalData($ticker, $startDate, $endDate);
        $query = $hist['query'];
        $results = $query['results'];
        $quote = $results['quote'];
        foreach ($quote as $day => $data) {
            $histData[$day] = [(string)$day, (float)$data['Low'], (float)$data['Open'], (float)$data['Close'], (float)$data['High']];
        }
        return $histData;
    }

    // Gets the stock current information and price
    public static function getCurrentData($ticker) {

        $client = new \Scheb\YahooFinanceApi\ApiClient();
        $currentData = $client->getQuotes($ticker);
        $query = $currentData['query'];
        $results = $query['results'];
        $quote = $results['quote'];
        return $quote;
    }

    // this determines if the stock ticker exists.  Since the api throws an
    // exception if the stock doesn't exist, need to handle this with a try-catch
    public static function findStock($ticker) {
        $client = new \Scheb\YahooFinanceApi\ApiClient();

        try {
            $data = $client->search($ticker);
        }
        catch (ApiException $e) {
            //dump("catching exception - stock doesn't exist");
            return null;
        }

        //dump("stock exists");
        return $data;
    }
}
