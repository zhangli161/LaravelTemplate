<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/8/29
 * Time: 15:55
 */

namespace App\Components;


use App\Models\Template;

class TemplateManager extends Manager
{
	
	function __construct()
	{
		$this->keys = ['value'];
		
		$this->module =\App\Models\Template::class;
	}
}