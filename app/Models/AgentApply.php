<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AgentApply extends Model
{
    protected $fillable=["user_id","real_name","gender","telephone","address","region_id","wx","qq","email","business","store"];
	public function user(){
		return $this->belongsTo(User::class,'user_id');
	}
	public function region(){
		return $this->belongsTo(NativePlaceRegion::class,'region_id');
	}
}
