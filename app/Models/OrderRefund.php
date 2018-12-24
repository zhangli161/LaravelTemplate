<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    protected $fillable=['order_sku_id','amount','reason','status','payment','note'];
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function order_skus(){
        return $this->belongsTo(OrderSKU::class,'order_sku_id');
    }

}
