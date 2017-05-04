<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConnectExchangesAndStocks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stocks', function (Blueprint $table) {

            # Remove the field associated with the old way we were storing authors
            # Can do this here, or update the original migration that creates the `books` table
            # $table->dropColumn('author');

    		# Add a new INT field called `author_id` that has to be unsigned (i.e. positive)
            $table->integer('exchange_id')->unsigned();

    		# This field `author_id` is a foreign key that connects to the `id` field in the `authors` table
            $table->foreign('exchange_id')->references('id')->on('exchanges');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            Schema::table('stocks', function (Blueprint $table) {

                # ref: http://laravel.com/docs/migrations#dropping-indexes
                # combine tablename + fk field name + the word "foreign"
                $table->dropForeign('stocks_exchange_id_foreign');
                $table->dropColumn('exchange_id');

            });
    }
}
