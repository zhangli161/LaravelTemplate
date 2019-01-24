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
use Encore\Admin\Grid\Tools\BatchActions;
use Illuminate\Support\Facades\Request;
use Encore\Admin\Grid\Tools\BatchAction;

class Fahuodan extends BatchActions
{
	protected $action;
	
	public function __construct()
	{
	
	}
	
	public function script()
	{
		return <<<EOT

$('{$this->getElementClass()}').on('click', function() {
	 var url = "http://localhost/admin/export/order?id=$this->id";
	window.location.href=url;
//    $.pjax({container:'#pjax-container', url: url ,data: {
//            ids:selectedRows(),
//        }
//        });
});

EOT;
	
	}
	
}