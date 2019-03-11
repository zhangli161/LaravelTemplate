<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/8
 * Time: 13:52
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class Category extends Model
{
    use SoftDeletes, ModelTree, AdminBuilder;
    protected $table = 'category';  //表名
    protected $casts = ['attr' => 'json'];
    protected $guarded=[];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setParentColumn('parentid');
        $this->setOrderColumn('order');
        $this->setTitleColumn('name');
    }
}