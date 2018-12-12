<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Postage extends Model
{
	use SoftDeletes;    //使用软删除
	//
	protected $table = 'postage';  //表名
	protected $dates = ['deleted_at'];  //软删除
	
	public function regions(){
		return $this->belongsToMany(NativePlaceRegion::class,"postage_regions","postage_id","region_id");
	}
	public function postage_regions(){
		return $this->hasMany(PostageRegions::class,"postage_id");
	}
}
