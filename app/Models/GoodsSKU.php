<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSKU extends Model
{
	use SoftDeletes;    //使用软删除
	protected $table = 'goods_sku';  //表名
	protected $dates = ['deleted_at'];  //软删除
	protected $fillable = [
		'spu_no','sku_name', 'price', 'stock','stock_type','postage','order'
	];
	public function sku_postages(){
		return $this->hasMany('App\Models\GoodsSKUPostage','sku_id','id');
	}
	public function sku_spec_values(){
		return $this->hasMany(GoodsSKUSpecValue::class,'sku_id','id');
	}
	public function spu(){
		return $this->belongsTo(GoodsSPU::class,'spu_id','id');
	}
	public function spec_values(){
		return $this->belongsToMany(GoodsSpecValue::class,'goods_sku_spec_value','sku_id','spec_value_id')
			->withTimestamps();
	}
	public function postages(){
		return $this->belongsToMany(Postage::class,'goods_sku_postage','sku_id','postage_id')
			->withTimestamps();
	}
	
}
