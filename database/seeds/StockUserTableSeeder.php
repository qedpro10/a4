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
            'BAC' => ['jamal@harvard.edu'],
            'MCD' => ['jill@harvard.edu', 'qedpro@hotmail.com'],
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
