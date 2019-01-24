<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponDistributeMethod extends Model
{
	protected $fillable = [
		'method','price', 'send_message', 'stock','limit_per_user','cooldown',"attr"
	];
	protected $casts = ['attr' => 'json',];//内嵌字段
    public function coupon(){
    	return $this->belongsTo(Coupon::class,'coupon_id');
    }
}
