<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/8/29
 * Time: 16:08
 */

namespace App\Http\Controllers;


use App\Components\TemplateManager;

class DemoController extends Controller
{
	public function test(){
	
	}
	
	//Manager的用法
	public function test1(){
		$mgr=new TemplateManager();
		
		$template=$mgr->createObject();
		$template=$mgr->set($template,['value'=>'aaaaa']);
		$template->save();
		return $mgr->getList();
	}
	
	
}