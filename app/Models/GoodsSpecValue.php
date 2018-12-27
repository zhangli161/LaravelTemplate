<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSpecValue extends Model
{
	use SoftDeletes;    //使用软删除
	protected $table = 'goods_spec_value';  //表名
	protected $dates = ['deleted_at'];  //软删除
    protected $fillable=['value'];
	public function spec(){
		return $this->belongsTo(GoodsSpec::class,'spec_id','id');
	}
}
