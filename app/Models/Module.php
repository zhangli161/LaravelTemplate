<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function skus()
    {
        return $this->belongsToMany(GoodsSKU::class, "module_skus", "module_id", "sku_id");
    }
    public function module_skus(){
        return $this->hasMany(ModuleSKU::class,"module_id");
    }
}
