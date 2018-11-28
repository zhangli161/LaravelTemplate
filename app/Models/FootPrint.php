<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class FootPrint extends Model
{
//	use HasTimestamps;
	protected $fillable = ['user_id', 'spu_id'];
	public function spu(){
		return $this->belongsTo(GoodsSPU::class,'spu_id');
	}
}
