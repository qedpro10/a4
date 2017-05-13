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
        //dd($quote);
        return $quote;
    }

    // Gets the stock historical data for the given date range
    public static function getMovingData($ticker, $startDate, $endDate) {

        $client = new \Scheb\YahooFinanceApi\ApiClient();
        $hist = $client->getHistoricalData($ticker, $startDate, $endDate);
        $query = $hist['query'];
        $results = $query['results'];
        $quote = $results['quote'];
        foreach ($quote as $day => $data) {
            $histData[$day] = [(int)$day, (float)$data['Close']];
        }
        //dd($histData);

        //dd();
        return $histData;
    }


    // this determines if the stock ticker exists.  Since the api throws an
    // exception if the stock doesn't exist, need to handle this with a try-catch
    // the yahoo api search API does not work reliably so I'm doing a getQuotes
    // and determining if the data is valid.
    public static function findStock($ticker) {
        $client = new \Scheb\YahooFinanceApi\ApiClient();

        try {
            // search api is not working
            //$data = $client->search($ticker);
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

    public function convertExchangeName($yahooEx) {
        switch ($yahooEx) {
            case 'NYQ':
                $name='NYSE';
                break;
            case 'NMS':
                $name='NASDAQ';
                break;
            default:
                dd($yahooEx);
                $id = 3;
        }
        return $id;
    }
}
