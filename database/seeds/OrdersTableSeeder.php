<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sku1 = \App\Models\GoodsSKU::find(1);
        $sku2 = \App\Models\GoodsSKU::find(2);

        for ($i = 1; $i <= 3000; $i++) {
            $time=\Carbon\Carbon::createFromTimestamp(1514736000 + rand(0, 31536000));
            DB::table('orders')->insert([
                'id' => $i,
                'payment' => rand(100, 1000) / 10.0,
                'payment_type' => 1,
                'postage' => 1,
                "post_fee" => 0,
                "status" => 5,
                "completed_at" => $time,
                "user_id" => 1,
                "receiver_name" => "测试订单",
                "receiver_phone" => "测试订单",
                "receiver_address" => "测试订单",
                "receiver_region_id" => \App\Models\NativePlaceRegion::where('have_children', '<>', '1')->inRandomOrder()->first()->region_id,
                "buyer_nick" => "测试"
            ]);

            DB::table("order_skus")->insert([
                "order_id" => $i,
                "sku_id" => 1,
                "sku_name" => $sku1->sku_name,
                "thumb" => "封面图",
                "amount"=>1,
                "price"=>$sku1->price,
                "total_price"=>$sku1->price,
                "average_price"=>$sku1->price,
                "created_at"=>$time
            ]);

            DB::table("order_skus")->insert([
                "order_id" => $i,
                "sku_id" => 2,
                "sku_name" => $sku2->sku_name,
                "thumb" => "封面图",
                "amount"=>2,
                "price"=>$sku2->price,
                "total_price"=>2*$sku2->price,
                "average_price"=>$sku2->price,
                "created_at"=>$time
            ]);
        }
    }
}
