<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
	public function spu(){
		return $this->belongsTo(GoodsSPU::class,'spu_id','id');
	}
	protected $fillable=['url','sku_id','order'];
}
