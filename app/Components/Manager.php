<?php

/**
 * Created by PhpStorm.
 * User: Zhangli
 * Date: 2018-04-02
 * Time: 10:30
 * 模版Manager
 */

namespace App\Components;

abstract class Manager
{
	protected static $Modle;
	
	protected static $keys = ['id'];
	
	protected static $primary_key = 'id';
	
	//返回Modle::all()
	public static function getModle()
	{
		return static::$Modle::query();
	}
	
	/*
	 * 创建新的对象
	 *
	 * by Zhangli
	 *
	 * 2018/07/05
	 */
	public static function createObject()
	{
		return new static::$Modle();
	}
	
	/*
	 * 获取template的list
	 *
	 * By Zhangli
	 *
	 * 2018-04-02
	 */
	public static function getList(...$orderby)
	{
		if (!$orderby) {
			$templates = self::getModle()->orderby(static::$primary_key, 'desc');
			
		} else {
			if (count($orderby) > 1)
				for ($i = 0; $i < (count($orderby) - 1); $i += 2) {
					$templates = self::getModle($orderby[$i])
						->orderby($orderby[$i], $orderby[$i + 1]);
				}
			elseif (gettype($orderby[0]) == 'array') {
				$orderby = $orderby[0];
				for ($i = 0; $i < (count($orderby) - 1); $i += 2) {
					$templates = self::getModle($orderby[$i])
						->orderby($orderby[$i], $orderby[$i + 1]);
				}
			}
			
		}
		
		return $templates->get();
	}
	
	/*
	 * 根据id获取
	 *
	 * By Zhangli
	 *
	 * 2018-04-02
	 */
	public static function getById($id)
	{
		$template = self::getModle()->where(static::$primary_key, '=', $id)->first();
		return $template;
	}
	
	/*
	 * 根据条件数组获取
	 *
	 * By Zhangli
	 *
	 * 2018-07-18
	 */
	public static function getByCon(array $ConArr, $paginate = false, $orderby = ['id', 'asc'])
	{
		
		$templates = self::getModle()->orderby($orderby['0'], $orderby['1']);
		
		foreach ($ConArr as $key => $value) {
			$templates = $templates->whereIn($key, $value);
		}
		if (!$paginate)
			$templates = $templates->get();
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
	public static function set($template, $data)
	{
		foreach (static::$keys as $key)
			if (array_key_exists($key, $data)) {
				$template[$key] = array_get($data, $key);
			}
		return $template;
	}
}