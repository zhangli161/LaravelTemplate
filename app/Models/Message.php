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
	protected $fillable = [
		'content_id', 'attr','status','form_user_id','to_user_id'
	];
	public function form_user()
	{
		return $this->belongsTo(User::class,'form_user_id');
	}
	public function to_user()
	{
		return $this->belongsTo(User::class,'to_user_id');
	}
	public function content(){
		return $this->belongsTo(MessageContent::class,'content_id','id');
	}
}