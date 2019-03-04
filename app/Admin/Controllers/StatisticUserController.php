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
        $this->request = $request;
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
            ->row($this->grid($request))
            ->row($this->chartform("/admin/statistic/user"))
            ->row("<script>disablePjax=true</script>");
    }


    /**
     * Make a grid builder.
     *
     * @return mixed
     */
    protected function grid(Request $request)
    {
        $rows = array();

        if ($request->filled('name'))
        {
            $users=User::with(["orders"])
                ->where("name","like","%".$request->get("name")."%")
                ->get();
        }
        else
        $users = User::with(["orders"])->get();
        $sum_row = ["总计", "", 0, 0.0, 0, 0];
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
            $sum_row[2] += $user->orders->count();
            $sum_row[3] += $user->orders->sum("payment");
            $sum_row[4] += $refunds->count();
            $sum_row[5] += $refunds->sum("payment");

        }
        $collection = collect($rows);
        if ($request->get("opt") == "desc")
            $sorted = $collection->sortByDesc($request->get("orderBy", "0"));
        else {
            $sorted = $collection->sortBy($request->get("orderBy", "0"));
        }
//        $rows->sortByDesc(0);
//        dd($collection->toArray(),$sorted->values()->all());
        $sorted->push($sum_row);
        $titles = ['用户id', '昵称', "订单数量", "总消费额", "退款次数", "退款金额"];
        $rows = $sorted->toArray();

        return view('admin.table.index', ['titles' => $titles, "rows" => $rows]);
    }

    protected function chartform($action)
    {
        $form = new Form(new StatisticOrder);
        $form->setTitle("排序");
        $form->tools(function (Form\Tools $tools) {

            // 去掉`列表`按钮
            $tools->disableList();
        });
        $form->footer(function ($footer) {

            // 去掉`重置`按钮
            $footer->disableReset();

            // 去掉`查看`checkbox
            $footer->disableViewCheck();

            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();

            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();

        });
        $form->setAction($action);
        $form->text('name', '昵称')
            ->default($this->request->get("name", ""));
        $form->select('orderBy', '排序方式')
            ->options([
                "0" => "ID",
                "2" => "订单数量",
                "3" => "总消费额",
                "4" => "退款次数",
                "5" => "退款金额"
            ])
            ->default($this->request->get("orderBy", "0"));
        $form->select("opt", "顺序")
            ->options(["asc" => "正序", "desc" => "倒序"])
            ->default($this->request->get("opt", "asc"));

//        $form->text("aaaa","bbb")->default(json_encode($this->request->toArray()));
        return $form;
    }

}
