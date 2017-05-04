<?php

use Illuminate\Database\Seeder;
use App\Stock;
use App\Exchange;

class StocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Load json file into PHP array
        $stocks = json_decode(file_get_contents(database_path().'/stocks.json'), True);
        # Initiate a new timestamp we can use for created_at/updated_at fields
        $timestamp = Carbon\Carbon::now()->subDays(count($stocks));
        foreach($stocks as $ticker => $stock) {

            # Set the created_at/updated_at for each stock to be one day less than
            # the stock before. That way each stock will have unique timestamps.
            $timestampForStock = $timestamp->addDay()->toDateTimeString();
            $exchange_short = $stock['exchange'];
            $exchange_id = Exchange::where('exchange_short', '=', $exchange_short)->pluck('id')->first();

            Stock::insert([
                'created_at' => $timestampForStock,
                'updated_at' => $timestampForStock,
                'ticker' => $ticker,
                'company_name' => $stock['company_name'],
                'logo' => $stock['logo'],
                'website' => $stock['website'],
                'dividend' => $stock['dividend'],
                'exchange_id' => $exchange_id,
                //'user_id' => 1, # <--- NEW LINE
            ]);
        }
    }
}
