<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // see the exchanges first as they are used by the stocks
        $this->call(ExchangesTableSeeder::class);
        $this->call(StocksTableSeeder::class);
    }
}
