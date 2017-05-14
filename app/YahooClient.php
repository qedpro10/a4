<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use Scheb\YahooFinanceApi\Exception\HttpException;
use Scheb\YahooFinanceApi\Exception\ApiException;

class YahooClient
{
    // Gets the stock historical data for the given date range
    public static function getHistoricalData($ticker, $startDate, $endDate) {

        try {
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
        catch (ApiException $e) {
            return null;
        }

    }

    // Gets the stock current information and price
    public static function getCurrentData($ticker) {

        try {
            $client = new \Scheb\YahooFinanceApi\ApiClient();
            $currentData = $client->getQuotes($ticker);
            $query = $currentData['query'];
            $results = $query['results'];
            $quote = $results['quote'];

            return $quote;
        }
        catch (ApiException $e) {
            return null;
        }
    }

    // Gets the stock historical data for the given date range
    public static function getMovingData($ticker, $startDate, $endDate) {
        try {
            $client = new \Scheb\YahooFinanceApi\ApiClient();
            $hist = $client->getHistoricalData($ticker, $startDate, $endDate);
            $query = $hist['query'];
            $results = $query['results'];
            $quote = $results['quote'];
            foreach ($quote as $day => $data) {
                $histData[$day] = [(int)$day, (float)$data['Close']];
            }
            return $histData;
        }
        catch (ApiException $e) {
            return null;
        }

    }


    // this determines if the stock ticker exists.  Since the api throws an
    // exception if the stock doesn't exist, need to handle this with a try-catch
    // the yahoo api search API does not work reliably so I'm doing a getQuotes
    // and determining if the data is valid.
    public static function findStock($ticker) {
        try {
            // search api is not working so this is a workaround to searching for
            // stock on the YAHOO site
            $client = new \Scheb\YahooFinanceApi\ApiClient();
            $data = $client->getQuotes($ticker);
            $query = $data['query'];
            $results = $query['results'];
            $quote = $results['quote'];
            //dd($quote);
            // check daysLow - if this is null the stock does not exist
            // yeah this is a hack but YAHOO API is not working correctly and
            // I needed to do something
            if($quote['DaysLow'] == null) {
                return null;
            }
            else {
                // get the needed data out of the quote and return that
                return $quote;
            }

        }
        catch (ApiException $e) {
            return null;
        }
    }

    // this function is supposed to analyze the historical to determine if there is a BEP
    // however the YAHOO data is not sufficient for this, so I am making up a pseudo algorithm
    // to simulate what the recommendations would be.  They are not accurate - Don't buy
    // based on these recommendations.  LOL
    public static function analyze($data, $hdata) {
        //dd($data);
        if (is_null($data)) return "UNKNOWN";

        if ($data['TwoHundreddayMovingAverage'] > 0) {
            if (($data['FiftydayMovingAverage'] < 0) &&
                ($data['PercentChangeFromFiftydayMovingAverage'] > 0)) {
                return "BUY";
            }
            else {
                return "WAIT";
            }
        }
        else {
            return "WAIT";
        }
    }
}
