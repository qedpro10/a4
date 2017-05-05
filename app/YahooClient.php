<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class YahooClient
{
    public static function getHistoricalData($ticker, $startDate, $endDate) {

        $client = new \Scheb\YahooFinanceApi\ApiClient();
        $hist = $client->getHistoricalData($ticker, $startDate, $endDate);
        $query = $hist['query'];
        $results = $query['results'];
        $quote = $results['quote'];
        foreach ($quote as $day => $data) {
            $histData[$day] = [(string)$day, (float)$data['Low'], (float)$data['Open'], (float)$data['Close'], (float)$data['High']];
            //$item = array($day, $data['Low'], $data['Open'], $data['Close'], $data['High']);
            //$histData[] = $item;
        }
        //dump($histData);
        //echo json_encode($histData);
        // parse the data into the candlestick array format
        // day, low, open, close, high
        return $histData;
    }

    public static function getCurrentData($ticker) {

        $client = new \Scheb\YahooFinanceApi\ApiClient();
        $currentData = $client->getQuotes($ticker);
        $query = $currentData['query'];
        $results = $query['results'];
        $quote = $results['quote'];
        return $quote;
    }

    public static function parseHistData($data) {

        dump($data);

        return $csData;
    }
}
