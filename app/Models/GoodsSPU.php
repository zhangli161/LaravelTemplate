<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSPU extends Model
{
	use SoftDeletes;    //使用软删除
    //
	protected $table = 'goods_spu';  //表名
	protected $dates = ['deleted_at'];  //软删除
	
	public function skus(){
		return $this->hasMany(GoodsSKU::class,'spu_id','id');
	}
	public function specs(){
		return $this->belongsToMany(GoodsSpec::class,'goods_spu_spec','spu_id','spec_id')
			->using(GoodsSPUSpec::class)->withTimestamps();
//		return $this->hasMany(GoodsSpecValue::class,'sku_id','id');
	}
	
}
