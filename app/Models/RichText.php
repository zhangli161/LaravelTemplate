<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RichText extends Model
{
	protected $fillable=['content'];
	public function item()
	{
		return $this->morphTo('item');
	}
}
