<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsSPUSence extends Model
{
    protected $table="goods_spu_sences";

    public function spu(){
        return $this->belongsTo(GoodsSPU::class);
    }
}
