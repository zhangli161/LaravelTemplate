<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GoodsSPUSpec extends Pivot
{
    //
	use SoftDeletes;    //使用软删除
	protected $table = 'goods_spu_spec';  //表名
	protected $dates = ['deleted_at'];  //软删除
}
