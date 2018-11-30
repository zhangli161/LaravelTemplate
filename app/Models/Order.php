<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
	protected $fillable = ['payment', 'payment_type', 'post_fee', 'user_id','buyer_nick',
		'receiver_name','receiver_phone','receiver_region_id','receiver_address'];
	
	public function skus()
	{
		return $this->hasMany(OrderSKU::class, 'order_id');
	}
	
	public function coupon()
	{
		return $this->hasOne(OrderCoupon::class, 'order_id');
	}
	
	public function postage()
	{
		return $this->hasOne(OrderPostage::class, 'order_id');
	}
}
