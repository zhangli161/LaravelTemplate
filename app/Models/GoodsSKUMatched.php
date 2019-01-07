<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSKUMatched extends Model
{
    protected $table="goods_sku_matched";
    protected $primaryKey="sku_id";
    protected $fillable=["matched_sku_id"];

    public function sku(){
        return $this->belongsTo(GoodsSKU::class,"sku_id");
    }
    public function matched_sku(){
        return $this->belongsTo(GoodsSKU::class,"matched_sku_id");
    }
}
