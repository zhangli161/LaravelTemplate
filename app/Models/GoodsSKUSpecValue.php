<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;


class GoodsSKUSpecValue extends Model
{
	use SoftDeletes;    //使用软删除
	protected $table = 'goods_sku_spec_value';  //表名
	protected $dates = ['deleted_at'];  //软删除
	protected $fillable=['spec_value_id','sku_id',"spec_id"];
	
	public function spec()
	{
		return $this->belongsTo(GoodsSpec::class,'spec_id','id');
	}
    public function spec_value()
    {
        return $this->belongsTo(GoodsSpecValue::class,'spec_value_id','id');
    }
	public function sku()
	{
		return $this->belongsTo(GoodsSKU::class,'sku_id','id');
	}
}
