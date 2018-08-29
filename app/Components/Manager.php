<?php

/**
 * Created by PhpStorm.
 * User: Zhangli
 * Date: 2018-04-02
 * Time: 10:30
 * 模版Manager
 */

namespace App\Components;

class Manager
{
	protected $module;
	
	protected $keys=['id'];
	
	protected $primary_key='id';
	/*
	 * 创建新的对象
	 *
	 * by Zhangli
	 *
	 * 2018/07/05
	 */
	public function createObject(){
		$template=new $this->module();
		//这里可以对新建记录进行一定的默认设置
		
		return $template;
	}
	
	
	/*
	 * 获取template的list
	 *
	 * By Zhangli
	 *
	 * 2018-04-02
	 */
	public function getList()
	{
		$templates = $this->module::orderby('id', 'desc')->get();
		return $templates;
	}
	
	/*
	 * 根据id获取
	 *
	 * By Zhangli
	 *
	 * 2018-04-02
	 */
	public function getById($id)
	{
		$template = $this->module::where($this->primary_key, '=', $id)->first();
		return $template;
	}
	
	/*
	 * 根据条件数组获取
	 *
	 * By Zhangli
	 *
	 * 2018-07-18
	 */
	public function getByCon(array $ConArr, $paginate = false, $orderby = ['id', 'asc'])
	{
		
		$templates = $this->module::orderby($orderby['0'], $orderby['1']);
		if (!$paginate)
			$templates = $templates->get();
		foreach ($ConArr as $key => $value) {
			$templates = $templates->whereIn($key, $value);
		}
		if ($paginate) {
			$templates = $templates->paginate(5);
		}
		return $templates;
	}
	
	
	/*
	 * 设置信息，用于编辑
	 *
	 * By Zhangli
	 *
	 * 2018-04-02
	 */
	public function set($template, $data)
	{
		foreach ($this->keys as $key)
		if (array_key_exists($key, $data)) {
			$template[$key] = array_get($data, $key);
		}
		return $template;
	}
}