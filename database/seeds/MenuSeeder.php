<?php

use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\Encore\Admin\Auth\Database\Menu::insert([
			[
				'id' => 18,
				'parent_id' => 0,
				'order' => 0,
				'title' => '商品管理',
				'icon' => 'fa-shopping-bag',
				'uri' => '',
			],
			[
				'id' => 19,
				'parent_id' => 18,
				'order' => 0,
				'title' => '商品列表',
				'icon' => 'fa-bars',
				'uri' => 'goods',
			],
			[
				'id' => 20,
				'parent_id' => 18,
				'order' => 0,
				'title' => '快递方式',
				'icon' => 'fa-truck',
				'uri' => 'postage',
			],
			[
				'id' => 21,
				'parent_id' => 18,
				'order' => 0,
				'title' => '规格',
				'icon' => 'fa-list',
				'uri' => 'spec',
			],
			
			
		]);
	}
}
