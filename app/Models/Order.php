<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $fillable = ['payment', 'payment_type', 'postage','post_fee', 'user_id','buyer_nick',"buyer_message",
		'receiver_name','receiver_phone','receiver_region_id','receiver_address'];
	
	public function user(){
		return $this->belongsTo(User::class,'user_id');
	}
	public function skus()
	{
		return $this->hasMany(OrderSKU::class, 'order_id');
	}
	
	public function coupon()
	{
		return $this->hasOne(OrderCoupon::class, 'order_id');
	}
	
	public function wuliu()
	{
		return $this->hasOne(OrderPostage::class, 'order_id');
	}
	
	public function xcx_pay(){
		return $this->hasOne(WeiXinXPay::class,'order_id');
	}

	public function refund(){
	    return $this->hasMany(OrderRefund::class,'order_id');
    }

    public function order_agent(){
	    return $this->hasOne(OrderAgent::class,"order_id");
    }
}
