<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
	protected $fillable = ['user_id', 'item_id', 'item_type'];
	
	public function item()
	{
		return $this->morphTo();
	}
}
