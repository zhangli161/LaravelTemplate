<?php

namespace App\Admin\Controllers;

use App\Components\ChartManager;
use App\Components\NativePalceReagionManager;
use App\Models\GoodsSKU;
use App\Models\GoodsSPU;
use App\Models\NativePlaceRegion;
use App\Models\Order;
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
use Encore\Admin\Widgets\Box;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StatisticUserController extends Controller
{
    use HasResourceActions;
    private $request;
    public function __construct(Request $request)
    {
        $this->request=$request;
    }
    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content, Request $request)
    {
        return $content
            ->header('消费者统计')
            ->row(function (Row $row) {
                $today = $this_week = Carbon::today();
                $this_week = Carbon::today()->startOfWeek();
//            ->lastOfMonth();
                $this_month = Carbon::today()->startOfMonth();
                $box0 = new Box("本日用户增长", User::where("created_at", ">=", $today)->count() . " 名");
                $box0->style("small-box bg-aqua");
                $row->column(4, $box0);

                $box1 = new Box("本周用户增长", User::where("created_at", ">=", $this_week)->count() . " 名");
                $box1->style("small-box bg-aqua");
                $row->column(4, $box1);

                $box2 = new Box("本月用户增长", User::where("created_at", ">=", $this_month)->count() . " 名");
                $box2->style("small-box bg-aqua");
                $row->column(4, $box2);
//                $row->column(4, 'baz');
            })
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

        $users = User::with(["orders"])->get();
        foreach ($users as $user) {
            $refunds = OrderRefund::whereIn("order_id", $user->orders->pluck("id")->toArray())->get();
            array_push($rows, [
                $user->id,
                $user->name,
                $user->orders->count(),//订单数量
                $user->orders->sum("payment"),//总消费额
                $refunds->count(),//退款次数
                $refunds->sum("payment")//退款金额
            ]);
        }

        $titles = ['用户id', '昵称', "订单数量", "总消费额", "退款次数", "退款金额"];

        return view('admin.table.index', ['titles' => $titles, "rows" => $rows]);
    }


}
