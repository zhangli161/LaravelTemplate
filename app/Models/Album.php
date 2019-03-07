<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
	public function sku()
	{
		return $this->belongsTo(GoodsSKU::class, 'sku_id', 'id');
	}
	
	protected $fillable = ['url', 'sku_id', 'order'];

}
