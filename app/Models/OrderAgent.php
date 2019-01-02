<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAgent extends Model
{
    public function order(){
        return $this->belongsTo(Order::class,"order_id");
    }
    public function agent(){
        return $this->belongsTo(Agent::class,"agent_id");
    }
}
