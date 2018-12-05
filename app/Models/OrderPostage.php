<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPostage extends Model
{
	protected $primaryKey='order_id';
    public function order(){
    	return $this->belongsTo(Order::class,'order_id');
    }
}
