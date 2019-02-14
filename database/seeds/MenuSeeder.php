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
                'order' => 9,
                'title' => '管理功能',
                'icon' => 'fa-cogs',
                'uri' => '',
            ],
            [
                'id' => 10,
                'parent_id' => 0,
                'order' => 10,
                'title' => '商品管理',
                'icon' => 'fa-shopping-bag',
                'uri' => '',
            ],
            [
                'id' => 11,
                'parent_id' => 0,
                'order' => 11,
                'title' => '订单管理',
                'icon' => 'fa-sticky-note-o ',
                'uri' => '',
            ],
            [
                'id' => 12,
                'parent_id' => 0,
                'order' => 12,
                'title' => '文章资讯',
                'icon' => 'fa-book',
                'uri' => 'article',
            ],
            [
                'id' => 13,
                'parent_id' => 0,
                'order' => 13,
                'title' => '代理商管理',
                'icon' => 'fa-black-tie',
                'uri' => '',
            ],
            [
                'id' => 14,
                'parent_id' => 0,
                'order' => 14,
                'title' => '统计报表',
                'icon' => 'fa-area-chart',
                'uri' => '',
            ],


            //用户管理
            [
                'id' => 15,
                'parent_id' => 8,
                'order' => 15,
                'title' => '用户列表',
                'icon' => 'fa-child',
                'uri' => 'users',
            ],

            //管理功能
            [
                'id' => 16,
                'parent_id' => 9,
                'order' => 16,
                'title' => '轮播图管理',
                'icon' => 'fa-image',
                'uri' => 'banner',
            ],
            [
                'id' => 17,
                'parent_id' => 9,
                'order' => 17,
                'title' => '小程序首页模块',
                'icon' => 'fa-plus-square',
                'uri' => 'module',
            ],
            [
                'id' => 18,
                'parent_id' => 9,
                'order' => 18,
                'title' => '分类管理',
                'icon' => 'fa-align-left',
                'uri' => 'category',
            ],
            [
                'id' => 19,
                'parent_id' => 9,
                'order' => 19,
                'title' => '消息管理',
                'icon' => 'fa-bullhorn',
                'uri' => '',
            ],
            [
                'id' => 20,
                'parent_id' => 19,
                'order' => 20,
                'title' => '群发消息',
                'icon' => 'fa-envelope-square',
                'uri' => 'message',
            ],
            [
                'id' => 21,
                'parent_id' => 19,
                'order' => 21,
                'title' => '消息源',
                'icon' => 'fa-male',
                'uri' => 'message_source',
            ],
            //商品管理
            [
                'id' => 22,
                'parent_id' => 10,
                'order' => 22,
                'title' => '商品列表',
                'icon' => 'fa-bars',
                'uri' => 'goods',
            ],
            [
                'id' => 23,
                'parent_id' => 10,
                'order' => 23,
                'title' => '细类商品列表',
                'icon' => 'fa-bars',
                'uri' => 'goods_sku',
            ],
            [
                'id' => 24,
                'parent_id' => 10,
                'order' => 24,
                'title' => '规格',
                'icon' => 'fa-list',
                'uri' => 'spec',
            ],
            [
                'id' => 25,
                'parent_id' => 10,
                'order' => 25,
                'title' => '优惠券',
                'icon' => 'fa-ticket',
                'uri' => 'coupon',
            ],
            [
                'id' => 26,
                'parent_id' => 10,
                'order' => 26,
                'title' => '优惠活动',
                'icon' => 'fa-dollar',
                'uri' => 'benefit',
            ],
            [
                'id' => 27,
                'parent_id' => 10,
                'order' => 27,
                'title' => '快递方式',
                'icon' => 'fa-truck',
                'uri' => 'postage',
            ],
            //订单
            [
                'id' => 28,
                'parent_id' => 11,
                'order' => 28,
                'title' => '查看订单',
                'icon' => 'fa-bars',
                'uri' => 'order',
            ],
            [
                'id' => 29,
                'parent_id' => 11,
                'order' => 29,
                'title' => '退款申请',
                'icon' => 'fa-dollar',
                'uri' => 'refund/order',
            ],
            //代理商管理
            [
                'id' => 30,
                'parent_id' => 13,
                'order' => 30,
                'title' => '代理商列表',
                'icon' => 'fa-bars',
                'uri' => 'agents',
            ],
            [
                'id' => 31,
                'parent_id' => 13,
                'order' => 31,
                'title' => '代理商申请',
                'icon' => 'fa-paper-plane',
                'uri' => 'apply/agent',
            ],

            //统计报表
            [
                'id' => 32,
                'parent_id' => 14,
                'order' => 32,
                'title' => '订单统计',
                'icon' => 'fa-align-left',
                'uri' => '',
            ],
            [
                'id' => 33,
                'parent_id' => 32,
                'order' => 33,
                'title' => '统计表格',
                'icon' => 'fa-wpforms',
                'uri' => 'statistic/order',
            ],
            [
                'id' => 34,
                'parent_id' => 32,
                'order' => 34,
                'title' => '订单量-时间统计图',
                'icon' => 'fa-align-left',
                'uri' => 'chart/order/count',
            ],
            [
                'id' => 35,
                'parent_id' => 32,
                'order' => 35,
                'title' => '订单量-时间统计图',
                'icon' => 'fa-align-left',
                'uri' => 'chart/order/payment',
            ],


            [
                'id' => 36,
                'parent_id' => 14,
                'order' => 36,
                'title' => '商品统计',
                'icon' => 'fa-paper-plane',
                'uri' => '',
            ],
            [
                'id' => 37,
                'parent_id' => 36,
                'order' => 37,
                'title' => '统计表格',
                'icon' => 'fa-wpforms',
                'uri' => 'statistic/goods',
            ],
            [
                'id' => 38,
                'parent_id' => 36,
                'order' => 38,
                'title' => '商品销量-时间统计图',
                'icon' => 'fa-align-left',
                'uri' => 'chart/goods/count',
            ],
            [
                'id' => 39,
                'parent_id' => 36,
                'order' => 39,
                'title' => '商品销售额-时间统计图',
                'icon' => 'fa-align-left',
                'uri' => 'chart/goods/payment',
            ],
            [
                'id' => 40,
                'parent_id' => 14,
                'order' => 40,
                'title' => '退款统计',
                'icon' => 'fa-paper-plane',
                'uri' => 'statistic/refund',
            ],
            [
                'id' => 41,
                'parent_id' => 14,
                'order' => 41,
                'title' => '财务统计',
                'icon' => 'fa-paper-plane',
                'uri' => '',
            ],
            [
                'id' => 42,
                'parent_id' => 41,
                'order' => 42,
                'title' => '统计表格',
                'icon' => 'fa-wpforms',
                'uri' => 'statistic/finance',
            ],
            [
                'id' => 43,
                'parent_id' => 41,
                'order' => 43,
                'title' => '净收入-时间统计图',
                'icon' => 'fa-align-left',
                'uri' => 'chart/finance/income',
            ],
            [
                'id' => 44,
                'parent_id' => 14,
                'order' => 44,
                'title' => '消费者统计',
                'icon' => 'fa-paper-plane',
                'uri' => 'statistic/user',
            ],
            [
                'id' => 45,
                'parent_id' => 14,
                'order' => 45,
                'title' => '优惠券统计',
                'icon' => 'fa-paper-plane',
                'uri' => 'statistic/coupon',
            ],
            [
                'id' => 46,
                'parent_id' => 14,
                'order' => 46,
                'title' => '代理商统计',
                'icon' => 'fa-paper-plane',
                'uri' => '',
            ],
            [
                'id' => 47,
                'parent_id' => 46,
                'order' => 47,
                'title' => '统计表格',
                'icon' => 'fa-wpforms',
                'uri' => 'statistic/agent',
            ],
        ]);
        for ($i = 8; $i <= 47; $i++) {
            DB::table('admin_role_menu')->insert([
                "role_id" => 1,
                "menu_id" => $i
            ]);
        }
        DB::table('admin_roles')->insert([
            "name" => "代理商",
            "slug" => "agent"
        ]);


    }
}
