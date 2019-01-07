<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSKUSimilar extends Model
{
    protected $table="goods_sku_similars";
    protected $primaryKey="sku_id";
    protected $fillable=["similar_sku_id"];

    public function sku(){
        return $this->belongsTo(GoodsSKU::class,"sku_id");
    }
    public function similar_sku(){
        return $this->belongsTo(GoodsSKU::class,"similar_sku_id");
    }
}
