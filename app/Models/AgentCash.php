<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AgentCash extends Model
{
    protected $fillable=["user_id","amount"];
    protected $casts=["return"=>"array"];
    public function agent(){
        return $this->belongsTo(Agent::class,"agent_id");
    }
    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }

}
