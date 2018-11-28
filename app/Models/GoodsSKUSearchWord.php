<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSKUSearchWord extends Model
{
	protected $table='goods_sku_search_words';
	protected $fillable = ['search_words'];
	protected $primaryKey='sku_id';
	public function sku()
	{
		return $this->belongsTo(GoodsSKU::class, 'sku_id');
	}
}
