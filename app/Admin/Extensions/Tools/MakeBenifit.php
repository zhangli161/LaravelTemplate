<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/12/6
 * Time: 14:00
 */

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Tools\AbstractTool;
use Illuminate\Support\Facades\Request;
use Encore\Admin\Grid\Tools\BatchAction;

class MakeBenifit extends BatchAction
{
	protected $action;
	
	public function __construct()
	{
	
	}
	
	public function script()
	{
		return <<<EOT

$('{$this->getElementClass()}').on('click', function() {
	 var url = '{$this->resource}s/make_benifit?ids='+selectedRows();
	window.location.href=url;
//    $.pjax({container:'#pjax-container', url: url ,data: {
//            ids:selectedRows(),
//        }
//        });
});

EOT;
	
	}
	
}