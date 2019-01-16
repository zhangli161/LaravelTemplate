<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgentApply extends Model
{
    use SoftDeletes;
    protected $fillable=["user_id","real_name","gender","telephone","address","region_id","wx","qq","email","business","store"];
    protected $casts = ['store' => 'json',];//内嵌字段
    protected $dates=["deleted_at"];
    	public function user(){
		return $this->belongsTo(User::class,'user_id');
	}
	public function region(){
		return $this->belongsTo(NativePlaceRegion::class,'region_id');
	}
}
