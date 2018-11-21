<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSpec extends Model
{
	use SoftDeletes;    //使用软删除
	protected $table = 'goods_spec';  //表名
	protected $dates = ['deleted_at'];  //软删除
	public function values(){
		return $this->hasMany(GoodsSpecValue::class,'spec_id','id');
	}
}
