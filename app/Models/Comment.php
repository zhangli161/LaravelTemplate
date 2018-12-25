<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['star','star_1','star_2','star_3','content','albums'];
    protected $casts = ['albums' => 'array',];//内嵌字段

    public function spu()
    {
        return $this->belongsTo(GoodsSPU::class,'spu_id');
    }
    public function sku()
    {
        return $this->belongsTo(GoodsSKU::class,'sku_id');
    }
    public function order_sku()
    {
        return $this->belongsTo(OrderSKU::class,'order_sku_id');
    }
}
