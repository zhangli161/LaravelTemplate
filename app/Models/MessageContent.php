<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/8
 * Time: 15:41
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MessageContent extends Model
{
	use SoftDeletes;
	protected $table = 'message_content';  //表名
	protected $dates = ['deleted_at'];  //软删除
	protected $fillable = [
		'title', 'content','send_type','source_id','attr'
	];
    protected $casts = ['attr' => 'json',];//内嵌字段
	public function source()
	{
		return $this->hasOne(MessageSource::class,'id','source_id');
	}
	public function message(){
			return $this->hasMany(Message::class,'content_id','id');
	}
	public function content(){
		return $this->morphOne(RichText::class,'item');
	}
}