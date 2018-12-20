<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->insert([
            'name' => '商品分类',
            'parentid' => '0',
        ]);
        DB::table('category')->insert([
            'name' => '场景分类',
            'parentid' => '0',
        ]);
        DB::table('category')->insert([
            'name' => '文章分类',
            'parentid' => '0',
        ]);
        DB::table('category')->insert([
            'name' => 'LED灯丝灯',
            'parentid' => '1',
        ]);
        DB::table('category')->insert([
            'name' => 'SMD灯泡',
            'parentid' => '1',
        ]);
        DB::table('category')->insert([
            'name' => '装饰性大灯泡',
            'parentid' => '1',
        ]);
        DB::table('category')->insert([
            'name' => '吊线灯头',
            'parentid' => '1',
        ]);
        DB::table('category')->insert([
            'name' => '客厅',
            'parentid' => '2',
        ]);
        DB::table('category')->insert([
            'name' => '卧室',
            'parentid' => '2',
        ]);
        DB::table('category')->insert([
            'name' => '餐厅',
            'parentid' => '2',
        ]);
        DB::table('category')->insert([
            'name' => '吧台',
            'parentid' => '2',
        ]);
        DB::table('category')->insert([
            'name' => '阳台',
            'parentid' => '2',
        ]);
        DB::table('category')->insert([
            'name' => '企业历史',
            'parentid' => '3',
        ]);
        DB::table('category')->insert([
            'name' => '产品知识',
            'parentid' => '3',
        ]);
        DB::table('category')->insert([
            'name' => 'Calex 在荷兰',
            'parentid' => '3',
        ]);
        DB::table('category')->insert([
            'name' => 'Calex 公益',
            'parentid' => '3',
        ]);

    }
}
