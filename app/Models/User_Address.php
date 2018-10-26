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

class User_Address extends Model
{
    use SoftDeletes;    //使用软删除
//    protected $connection = 'sxwdb';   //数据库名
    protected $table = 'user_address';  //表名
//    public $timestamps = false;       //不使用时间戳
//	protected $primaryKey = 'id';       //主键
    protected $dates = ['deleted_at'];  //软删除
//	protected $hidden = ['password'];//隐藏的字段
	
	public function user()
	{
		return $this->belongsTo(User::class,'user_id','id');
	}
	public function region()
	{
		return $this->belongsTo(Native_Place_Region::class,'region_id','region_id');
	}
}