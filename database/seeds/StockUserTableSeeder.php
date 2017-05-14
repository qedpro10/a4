<?php

use Illuminate\Database\Seeder;
use App\Stock;
use App\User;

class StockUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stocks =[
            'CIEN' => ['qedpro@hotmail.com', 'psmith@hotmail.com'],
            'BAC' => ['jamal@harvard.edu', 'psmith@hotmail.com'],
            'MCD' => ['jill@harvard.edu', 'qedpro@hotmail.com'],
            'AAPL' => ['psmith@hotmail.com', 'qedpro@hotmail.com'],
            'AFL' => ['jill@harvard.edu'],
            'AST' => ['jill@harvard.edu'],
            'AMZN' => ['jill@harvard.edu'],
            'AAPL' => ['jill@harvard.edu'],
            'NFLX' => ['jill@harvard.edu'],
            'FB' => ['jill@harvard.edu'],
            'IBM' => ['jill@harvard.edu'],
            'CSCO' => ['jill@harvard.edu'],
            'K' => ['jill@harvard.edu'],
            'DIS' => ['jill@harvard.edu'],
        ];

        foreach($stocks as $ticker => $users) {

            # First get the stock
            $stock = Stock::where('ticker','LIKE', $ticker)->first();

            # Now loop through each user for this stock, adding the pivot
            // match on the email since we can ensure uniqueness here
            foreach($users as $userEmail) {
                $user = User::where('email','LIKE',$userEmail)->first();

                # Connect this user to this stock
                $stock->users()->save($user);
            }
        }
    }
}
