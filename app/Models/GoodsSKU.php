<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GoodsSKU extends Model
{
    use SoftDeletes;    //使用软删除
    protected $table = 'goods_sku';  //表名
    protected $dates = ['deleted_at'];  //软删除
    protected $fillable = [
        'sku_no', 'sku_name', 'price', 'stock', 'stock_type', 'postage', 'order'
    ];
    protected $appends = ['thumbs', 'spec_value_strs'];
//	//快递方式关联
//	public function sku_postages()
//	{
//		return $this->hasMany('App\Models\GoodsSKUPostage', 'sku_id', 'id');
//	}

    //规格值关联
    public function sku_spec_values()
    {
        return $this->hasMany(GoodsSKUSpecValue::class, 'sku_id', 'id');
    }

    //SPU
    public function spu()
    {
        return $this->belongsTo(GoodsSPU::class, 'spu_id', 'id');
    }

    //规格值
    public function spec_values()
    {
        return $this->belongsToMany(GoodsSpecValue::class, 'goods_sku_spec_value', 'sku_id', 'spec_value_id');
    }


//	//邮递方式
//	public function postages()
//	{
//		return $this->belongsToMany(Postage::class, 'goods_sku_postage', 'sku_id', 'postage_id')
//			->withTimestamps();
//	}

    //促销活动
    public function benefits()
    {
        return $this->hasMany(GoodsBenefit::class, 'sku_id');
    }

    //相册
    public function albums()
    {
        return $this->hasMany(Album::class, 'sku_id', 'id');
    }

    public function search_word()
    {
        return $this->hasOne(GoodsSKUSearchWord::class, 'sku_id');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'item');
    }

    public function matched_skus()
    {
        return $this->belongsToMany(GoodsSKU::class, "goods_sku_matched"
            , "sku_id", "matched_sku_id");
    }

    public function matched_sku_throughs()
    {
        return $this->hasMany(GoodsSKUMatched::class, "sku_id");
    }

    public function similar_skus()
    {
        return $this->belongsToMany(GoodsSKU::class, "goods_sku_similars"
            , "sku_id", "similar_sku_id");
    }

    public function similar_sku_throughs()
    {
        return $this->hasMany(GoodsSKUSimilar::class, "sku_id");
    }

    public function getThumbsAttribute()
    {
        return $this->albums()->pluck('url');
    }

    public function setThumbsAttribute(array $values)
    {

        $album_ids = [];
        $storage_url = Storage::disk('admin')->url('/');
        foreach ($values as $album) {
            $url = str_replace($storage_url, '', $album);
            $album_now = $this->albums()
                ->updateOrCreate(['url' => $url]);
            array_push($album_ids, $album_now->id);
        };
        $this->albums()->whereNotIn('id', $album_ids)->delete();
    }

    public function getSpecValueStrsAttribute()
    {
        if ($this->spec_values()->exists())
            return $this->spec_values->map(function ($spec_value, $key) {
                if ($spec_value->spec)
                    return $spec_value->spec->spec_name . ':' . $spec_value->value;
                else
                    return "规格丢失";
            });
        else
            return [];
    }
}
