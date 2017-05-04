<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function exchange() {
		# Stock belongs to Exchange
		# Define an inverse one-to-many relationship.
		return $this->belongsTo('App\Exchange');
	}
}
