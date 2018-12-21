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
				'id' => 8,
				'parent_id' => 0,
				'order' => 8,
				'title' => '用户管理',
				'icon' => 'fa-child',
				'uri' => 'users',
			],
			[
				'id' => 9,
				'parent_id' => 0,
				'order' => 12,
				'title' => '地区',
				'icon' => 'fa-map-marker',
				'uri' => 'regions',
			],
			[
				'id' => 10,
				'parent_id' => 8,
				'order' => 9,
				'title' => '用户列表',
				'icon' => 'fa-child',
				'uri' => 'users',
			],
			[
				'id' => 11,
				'parent_id' => 8,
				'order' => 10,
				'title' => '收货地址',
				'icon' => 'fa-map-o',
				'uri' => 'user_address',
			],
			[
				'id' => 12,
				'parent_id' => 9,
				'order' => 13,
				'title' => '地区列表',
				'icon' => 'fa-map-marker',
				'uri' => 'regions',
			],
			[
				'id' => 13,
				'parent_id' => 0,
				'order' => 11,
				'title' => '轮播图',
				'icon' => 'fa-image',
				'uri' => 'banner',
			],
			[
				'id' => 14,
				'parent_id' => 0,
				'order' => 0,
				'title' => '分类',
				'icon' => 'fa-align-left',
				'uri' => 'category',
			],
			[
				'id' => 15,
				'parent_id' => 0,
				'order' => 0,
				'title' => '消息',
				'icon' => 'fa-bullhorn',
				'uri' => '',
			],
			[
				'id' => 16,
				'parent_id' => 15,
				'order' => 0,
				'title' => '系统消息',
				'icon' => 'fa-envelope-square',
				'uri' => 'message',
			],
			[
				'id' => 17,
				'parent_id' => 15,
				'order' => 0,
				'title' => '消息源',
				'icon' => 'fa-male',
				'uri' => 'message_source',
			],
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
			[
				'id' => 22,
				'parent_id' => 18,
				'order' => 0,
				'title' => '促销活动',
				'icon' => 'fa-dollar',
				'uri' => 'benefit',
			],
			[
				'id' => 23,
				'parent_id' => 18,
				'order' => 0,
				'title' => '优惠券',
				'icon' => 'fa-ticket',
				'uri' => 'coupon',
			],
			[
				'id' => 24,
				'parent_id' => 0,
				'order' => 0,
				'title' => '订单',
				'icon' => 'fa-sticky-note-o',
				'uri' => 'order',
			],
			[
				'id' => 25,
				'parent_id' => 0,
				'order' => 0,
				'title' => '文章资讯',
				'icon' => 'fa-book',
				'uri' => 'article',
			],
			
		]);
	}
}
