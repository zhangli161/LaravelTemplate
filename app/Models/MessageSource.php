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

class MessageSource extends Model
{
	use SoftDeletes;
	protected $table = 'message_source';  //表名
	protected $dates = ['deleted_at'];  //软删除
	protected $fillable = [
		'name', 'code'
	];
}