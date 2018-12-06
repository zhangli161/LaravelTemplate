<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsBenefit extends Model
{
	use SoftDeletes;    //使用软删除
	protected $fillable = ["sku_id", "title", "desc", "price", "origin_price", "show_origin_price", "time_form", "time_to", "reset"];
	
	public function good()
	{
		return $this->belongsTo(GoodsSKU::class, 'sku_id', 'id');
	}
}
