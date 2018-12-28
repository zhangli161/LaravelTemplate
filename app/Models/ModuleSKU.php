<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleSKU extends Model
{
    protected $table = "module_skus";
    protected $fillable = ["sku_id"];
}
