<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsBenefit extends Model
{
	use SoftDeletes;    //使用软删除
	public function good(){
		return $this->belongsTo(GoodsSKU::class,'sku_id','id');
	}
}
