<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:19
 */

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use SoftDeletes;    //使用软删除
//    protected $connection = 'sxwdb';   //数据库名
    protected $table = 'users_address';  //表名
//    public $timestamps = false;       //不使用时间戳
//	protected $primaryKey = 'id';       //主键
    protected $dates = ['deleted_at'];  //软删除
//	protected $hidden = ['password'];//隐藏的字段
    protected $fillable = ['region_id', 'region_id_1', 'region_id_2', 'address', 'mobile', 'is_main','name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(NativePlaceRegion::class, 'region_id', 'region_id');
    }
    public function region_1()
    {
        return $this->belongsTo(NativePlaceRegion::class, 'region_id_1', 'region_id');
    }
    public function region_2()
    {
        return $this->belongsTo(NativePlaceRegion::class, 'region_id_2', 'region_id');
    }
}