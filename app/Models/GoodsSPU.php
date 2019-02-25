<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GoodsSPU extends Model
{
    use SoftDeletes;    //使用软删除
    //
    protected $table = 'goods_spu';  //表名
    protected $dates = ['deleted_at'];  //软删除
    protected $fillable = [
        'spu_no',
        'spu_name',
        'desc',
        'thumb',
        'cate_id',
        'status'
    ];

    //子商品
    public function skus()
    {
        return $this->hasMany(GoodsSKU::class, 'spu_id', 'id');
    }

    //规格
    public function specs()
    {
        return $this->belongsToMany(GoodsSpec::class, 'goods_spu_spec', 'spu_id', 'spec_id')
            ->using(GoodsSPUSpec::class)->withTimestamps();
    }

    //商品详情图文
    public function detail()
    {
        return $this->morphOne(RichText::class, 'item');
    }

    public function cate()
    {
        return $this->belongsTo(Category::class, "cate_id");
    }

    public function sences()
    {
        return $this->belongsToMany(Category::class, "goods_spu_sences", "spu_id", "sence_cate_id");
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'spu_id');
    }
}
