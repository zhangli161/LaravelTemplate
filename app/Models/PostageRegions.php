<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostageRegions extends Model
{
	protected $fillable=["region_id","postage_id"];
	protected $table="postage_regions";
	
	public function postage(){
		return $this->belongsTo(Postage::class,"postage_id");
	}
}
