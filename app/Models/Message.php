<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/8
 * Time: 15:41
 */

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
	use SoftDeletes;
	protected $table = 'message';  //表名
	protected $dates = ['deleted_at'];  //软删除
	protected $casts = ['attr' => 'json',];//内嵌字段
	public function form_user()
	{
		return $this->belongsTo(User::class,'form_userid');
	}
	public function to_user()
	{
		return $this->belongsTo(User::class,'to_userid');
	}
}