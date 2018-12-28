<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    public function skus(){
	    return $this->belongsToMany(GoodsSKU::class, 'coupon_skus', 'coupon_id', 'sku_id')
		    ->withTimestamps();
    }
    public function distribute_methods(){
    	return $this->hasMany(CouponDistributeMethod::class,'coupon_id');
    }
    public function user_coupons(){
        return $this->hasMany(UserCoupon::class,"coupon_id");
    }

}
