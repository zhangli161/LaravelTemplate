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
		'spu_no', 'sku_name', 'price', 'stock', 'stock_type', 'postage', 'order'
	];
	
//	//快递方式关联
//	public function sku_postages()
//	{
//		return $this->hasMany('App\Models\GoodsSKUPostage', 'sku_id', 'id');
//	}
	
	//规格值关联
	public function sku_spec_values()
	{
		return $this->hasMany(GoodsSKUSpecValue::class, 'sku_id', 'id');
	}
	
	//SPU
	public function spu()
	{
		return $this->belongsTo(GoodsSPU::class, 'spu_id', 'id');
	}
	
	//规格值
	public function spec_values()
	{
		return $this->belongsToMany(GoodsSpecValue::class, 'goods_sku_spec_value', 'sku_id', 'spec_value_id')
			->withTimestamps();
	}
	
//	//邮递方式
//	public function postages()
//	{
//		return $this->belongsToMany(Postage::class, 'goods_sku_postage', 'sku_id', 'postage_id')
//			->withTimestamps();
//	}
	
	//促销活动
	public function benefits()
	{
		return $this->hasMany(GoodsBenefit::class, 'sku_id');
	}
	
	//相册
	public function albums()
	{
		return $this->hasMany(Album::class, 'sku_id', 'id');
	}
	
	public function search_word()
	{
		return $this->hasOne(GoodsSKUSearchWord::class, 'sku_id');
	}
}
