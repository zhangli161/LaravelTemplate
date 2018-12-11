<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/12/10
 * Time: 17:38
 */

namespace App\Components;


use Encore\Admin\Auth\Database\Administrator;

class AdminManager
{
	public static function new_admin($username, $passowrd, $name = "新建管理员")
	{
		//检测是否用户名重复
		if (!Administrator::query()->where("username", $username)->exists()) {
			$admin = Administrator::create([
				"username" => $username,
				"password" => bcrypt($passowrd),
				"name" => $name
			]);
			return $admin;
		}
		return null;
	}
	
	public static function setRoles(Administrator $admin, $role_id)
	{
		$admin->roles()->attach($role_id);
		return $admin;
	}
	
}