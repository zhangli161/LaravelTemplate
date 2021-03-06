<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:19
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;    //使用软删除
    protected $table = 'banner';  //表名
//    public $timestamps = false;       //不使用时间戳
//	protected $primaryKey = 'id';       //主键
    protected $dates = ['deleted_at'];  //软删除
//	protected $hidden = ['password'];//隐藏的字段
	protected $casts = ['attr' => 'json',];//内嵌字段
	
	
}