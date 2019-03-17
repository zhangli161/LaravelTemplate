<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPostage extends Model
{
	protected $primaryKey='order_id';
	protected $fillable=["data","status"];
	protected $casts=["data"=>"array"];
    public function order(){
    	return $this->belongsTo(Order::class,'order_id');
    }
}
