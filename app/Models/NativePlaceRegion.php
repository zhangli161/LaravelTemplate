<?php

/**
 * Created by PhpStorm.
 * User: HappyQi
 * Date: 2017/9/28
 * Time: 10:19
 */

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NativePlaceRegion extends Model
{
	use ModelTree,AdminBuilder;
//	use SoftDeletes;    //使用软删除
//    protected $connection = 'sxwdb';   //数据库名
	protected $table = 'native_place_region';  //表名
    public $timestamps = false;       //不使用时间戳
	protected $primaryKey = 'region_id';       //主键
	public $incrementing = false;  //自增
//    protected $dates = ['deleted_at'];  //软删除
//	protected $hidden = ['password'];//隐藏的字段
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		
		$this->setParentColumn('parentid');
		$this->setOrderColumn('order');
		$this->setTitleColumn('region_name');
	}
	
	public function user_address()
	{
		return $this->hasMany(UserAddress::class);
	}

	public function children_regions()
    {
        return $this->hasMany(NativePlaceRegion::class,"parentid","region_id");
    }
}