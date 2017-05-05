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

    public function users()
    {
        # With timetsamps() will ensure the pivot table has its created_at/updated_at fields automatically maintained
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
