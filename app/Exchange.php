<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    public function stocks() {
		# Exchange has many stocks
		# Define a one-to-many relationship.
		return $this->hasMany('App\Stock');
	}
}
