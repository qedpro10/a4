<?php

use Illuminate\Database\Seeder;
use App\Exchange;

class ExchangesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Array of exchange data to add
        $exchanges = [
            ['NYSE', "New York Stock Exchange", 'EST'],
            ['NASDAQ', 'Nasdaq', 'EST'],
            ['TSE', 'Toronto Stock Exchange', 'EST'],
            ['NYSE_MKT', 'NYSE Small Cap Equity Market', 'EST'],
        ];

        # Initiate a new timestamp we can use for created_at/updated_at fields
        $timestamp = Carbon\Carbon::now()->subDays(count($exchanges));

        # Loop through each exchange, adding them to the database
        foreach($exchanges as $exchange) {

            # Set the created_at/updated_at for each book to be one day less than
            # the book before. That way each book will have unique timestamps.
            $timestampForThisExchange = $timestamp->addDay()->toDateTimeString();
            Exchange::insert([
                'created_at' => $timestampForThisExchange,
                'updated_at' => $timestampForThisExchange,
                'exchange_short' => $exchange[0],
                'description' => $exchange[1],
                'timezone' => $exchange[2],
            ]);
        }
    }
}
