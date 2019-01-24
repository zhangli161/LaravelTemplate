<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponBenefit extends Model
{
    public function coupon(){
        return $this->belongsTo(Coupon::class,"coupon_id");
    }
    public function content(){
        return $this->morphOne(RichText::class,'item');
    }
}
