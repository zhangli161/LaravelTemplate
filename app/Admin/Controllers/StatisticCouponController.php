<?php

namespace App\Admin\Controllers;

use App\Components\ChartManager;
use App\Components\NativePalceReagionManager;
use App\Models\Coupon;
use App\Models\GoodsSKU;
use App\Models\GoodsSPU;
use App\Models\NativePlaceRegion;
use App\Models\Order;
use App\Models\OrderCoupon;
use App\Models\OrderRefund;
use App\Models\OrderSKU;
use App\Models\StatisticOrder;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StatisticCouponController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content, Request $request)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->row($this->grid($request));
//            ->row($this->chartform("/admin/statistic/goods"));
    }


    /**
     * Make a grid builder.
     *
     * @return mixed
     */
    protected function grid(Request $request)
    {
        $rows = array();

        $coupons = Coupon::with(["user_coupons"])->get();
        foreach ($coupons as $coupon) {
            $user_coupon_ids=$coupon->user_coupons->pluck("id")->toArray();
            $order_coupons=OrderCoupon::whereIn("user_coupon_id",$user_coupon_ids);

            array_push($rows, [
                $coupon->id,
                $coupon->name,
                $coupon->user_coupons()->withTrashed()->count(),//领取数量
                $order_coupons->count(),//使用数量
                $coupon->user_coupons()->withTrashed()->sum("payment"),//总优惠金额
            ]);
        }

        $titles = ['优惠券id', '优惠券名称', "领取数量", "使用数量", "已优惠金额"];

        return view('admin.table.index', ['titles' => $titles, "rows" => $rows]);
    }


}
