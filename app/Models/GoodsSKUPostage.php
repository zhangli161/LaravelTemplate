<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;


class GoodsSKUPostage extends Model
{
	use SoftDeletes;    //使用软删除
	protected $table = 'goods_sku_postage';  //表名
	protected $dates = ['deleted_at'];  //软删除
	protected $fillable = [
		'sku_id','postage_id'
	];
	public function postage()
	{
		return $this->belongsTo(GoodsSpec::class,'postage_id','id');
	}
	public function sku()
	{
		return $this->belongsTo(GoodsSKU::class,'sku_id','id');
	}
}
