<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSKU extends Model
{
    protected $table = 'order_skus';
    protected $fillable = ['sku_id', 'sku_name', 'thumb', 'amount', 'price', 'total_price', 'average_price'];
    protected $appends = ['refund_status'];

    public function comment()
    {
        return $this->hasOne(Comment::class, 'order_sku_id');
    }

    public function sku()
    {
        return $this->belongsTo(GoodsSKU::class, 'sku_id');
    }

    public function refund()
    {
        return $this->hasMany(OrderRefund::class, "order_sku_id");
    }

    public function getRefundStatusAttribute()
    {
        if (!$this->refund()->exists()) {
            return null;
        } else {
            //以最新的一次退款为准
            $refund = $this->refund()->orderBy('created_at', 'desc')->first();
            return $refund->status;
        }
    }
}
