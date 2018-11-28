<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
	use SoftDeletes;
	protected $dates = ['deleted_at'];  //软删除
	protected $fillable = ['user_id', 'spu_id', 'sku_id', 'amount'];
	
	public function spu()
	{
		return $this->belongsTo(GoodsSPU::class, 'spu_id', 'id');
	}
	
	public function sku()
	{
		return $this->belongsTo(GoodsSKU::class, 'sku_id', 'id');
	}
}
