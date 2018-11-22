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
	
	//子商品
	public function skus(){
		return $this->hasMany(GoodsSKU::class,'spu_id','id');
	}
	//规格
	public function specs(){
		return $this->belongsToMany(GoodsSpec::class,'goods_spu_spec','spu_id','spec_id')
			->using(GoodsSPUSpec::class)->withTimestamps();
	}
	//商品详情图文
	public function detail(){
		return $this->morphOne(RichText::class,'item');
	}
	//相册
	public function albums(){
		return $this->hasMany(Album::class,'spu_id','id');
	}
}
