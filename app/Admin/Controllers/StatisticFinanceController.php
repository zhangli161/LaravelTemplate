<?php

namespace App\Admin\Controllers;

use App\Components\ChartManager;
use App\Components\NativePalceReagionManager;
use App\Models\AgentCash;
use App\Models\NativePlaceRegion;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\StatisticOrder;
use App\Http\Controllers\Controller;
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

class StatisticFinanceController extends Controller
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
            ->header('财务流水')
            ->description('财务流水')
            ->row($this->chartform("/admin/statistic/finance"))
            ->row($this->grid($request))
            ->row($this->income_chart($content, $request))
            ->row("<script>disablePjax=true</script>");

    }


    /**
     * Make a grid builder.
     *
     * @return mixed
     */
    protected function grid(Request $request)
    {
        $models = self::getModel($request);

        if (count($models[0]) < 1 && count($models[1]) < 1) {
            return "未找到数据";
        }

        $titles = ['时间', '订单收入', "退款金额", "经销商返利提现金额", "净收入"];
        $rows = array();

        $type = $request->filled("type") ? $request->get("type") : 2;
        if ($type == "0") {
            $model_group0 = $models[0]->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $model_group1 = $models[1]->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $model_group2 = $models[2]->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $dates = getDatesBetween(
                min($models[0]->min('created_at'), $models[1]->min('created_at'), $models[2]->min('created_at')),
                max($models[0]->max('created_at'), $models[1]->max('created_at'), $models[2]->max('created_at'))
            );
            foreach ($dates as $key => $lable) {
                array_push($rows, [
                    $lable,
                    $model_group0->get($lable, new Collection())->sum("payment"),
                    $model_group1->get($lable, new Collection())->sum("payment"),
                    $model_group2->get($lable, new Collection())->sum("amount")/100,
                    $model_group0->get($lable, new Collection())->sum("payment") - $model_group1->get($lable, new Collection())->sum("payment"),
                ]);
            }

        } elseif ($type == "2") {
            $model_group0 = $models[0]->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
            $model_group1 = $models[1]->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
            $model_group2 = $models[2]->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
            $dates = getDatesBetween(
                min($models[0]->min('created_at'), $models[1]->min('created_at'),$models[2]->min('created_at')),
                max($models[0]->max('created_at'), $models[1]->max('created_at'),$models[2]->max('created_at')),
                2
            );
            foreach ($dates as $key => $lable) {
                array_push($rows, [
                    $lable,
                    $model_group0->get($lable, new Collection())->sum("payment"),
                    $model_group1->get($lable, new Collection())->sum("payment"),
                    $model_group2->get($lable, new Collection())->sum("amount")/100,
                    $model_group0->get($lable, new Collection())->sum("payment") - $model_group1->get($lable, new Collection())->sum("payment"),
                ]);
            }
        } elseif ($type == "4") {
            $model_group0 = $models[0]->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $model_group1 = $models[1]->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $model_group2 = $models[2]->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $dates = getDatesBetween(
                min($models[0]->min('created_at'), $models[1]->min('created_at'),$models[2]->min('created_at')),
                max($models[0]->max('created_at'), $models[1]->max('created_at'),$models[2]->max('created_at')),
                4
            );
            foreach ($dates as $key => $lable) {
                array_push($rows, [
                    $lable,
                    $model_group0->get($lable, new Collection())->sum("payment"),
                    $model_group1->get($lable, new Collection())->sum("payment"),
                    $model_group2->get($lable, new Collection())->sum("amount")/100,
                    $model_group0->get($lable, new Collection())->sum("payment") - $model_group1->get($lable, new Collection())->sum("payment"),
                ]);
            }

        }

        return view('admin.table.index', ['titles' => $titles, "rows" => $rows]);
    }

    private static function getModel(Request $request)
    {
        $query1 = Order::query()->whereIn("status", ["5"]);
        $query2 = OrderRefund::query();
        $query3 = AgentCash::query()->where('status', '1');

        if ($request->filled("date_from")) {
            $query1->whereDate("created_at", ">=", $request->get("date_from"));
            $query2->whereDate("created_at", ">=", $request->get("date_from"));
            $query3->whereDate("created_at", ">=", $request->get("date_from"));
//            $query3->whereDate("created_at", '<=', $request->get("date_to"));
        }
        if ($request->filled("date_to")) {
            $query1->whereDate("created_at", '<=', $request->get("date_to"));
            $query2->whereDate("created_at", '<=', $request->get("date_to"));
            $query3->whereDate("created_at", ">=", $request->get("date_to"));
//            $query3->whereDate("created_at", '<=', $request->get("date_to"));
        }

        $model1 = $query1->orderBy('created_at', 'asc')->get();
        $model2 = $query2->orderBy('created_at', 'asc')->get();
        $model3 = $query3->orderBy('created_at', 'asc')->get();

        return [$model1, $model2, $model3];
    }

    public function income_chart(Content $content, Request $request)
    {
        $models = self::getModel($request);
//        return $models;
        //如果未获取的数据
//        if ($model->count() < 1)
//            return $content
//                ->header('商城净收入统计图')
////			->description('折线图')
//                ->row("未找到对应数据！")
//                ->row($this->chartform("/admin/chart/finance/income"));


        $description = "";
        $lables = array();
        $datas = array();
        $type = $request->filled("type") ? $request->get("type") : 2;
        if ($type == "0") {
            $model_group0 = $models[0]->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $model_group1 = $models[1]->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $dates = getDatesBetween(
                min($models[0]->min('created_at'), $models[1]->min('created_at')),
                max($models[0]->max('created_at'), $models[1]->max('created_at'))
            );
            foreach ($dates as $key => $lable) {
                $income = $model_group0->get($lable, new Collection())->sum("payment")
                    - $model_group1->get($lable, new Collection())->sum("payment");

//                array_push($rows, [
//                    $lable,
//                    $model_group0->get($lable, new Collection())->sum("payment"),
//                    $model_group1->get($lable, new Collection())->sum("payment"),
//                    0, $income
//                ]);
                $datas[$key] = round($income, 2);
            }
        } elseif ($type == "2") {
            $model_group0 = $models[0]->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
            $model_group1 = $models[1]->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
            $dates = getDatesBetween(
                min($models[0]->min('created_at'), $models[1]->min('created_at')),
                max($models[0]->max('created_at'), $models[1]->max('created_at')),
                2
            );
            foreach ($dates as $key => $lable) {
//                array_push($rows, [
//                    $lable,
//                    $model_group0->get($lable, new Collection())->sum("payment"),
//                    $model_group1->get($lable, new Collection())->sum("payment"),
//                    0,
//                    $model_group0->get($lable, new Collection())->sum("payment") - $model_group1->get($lable, new Collection())->sum("payment"),
//                ]);
                $income = $model_group0->get($lable, new Collection())->sum("payment")
                    - $model_group1->get($lable, new Collection())->sum("payment");
                $datas[$key] = round($income, 2);
            }

        } elseif ($type == "4") {
            $model_group0 = $models[0]->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $model_group1 = $models[1]->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $dates = getDatesBetween(
                min($models[0]->min('created_at'), $models[1]->min('created_at')),
                max($models[0]->max('created_at'), $models[1]->max('created_at')),
                4
            );
            foreach ($dates as $key => $lable) {
//                array_push($rows, [
//                    $lable,
//                    $model_group0->get($lable, new Collection())->sum("payment"),
//                    $model_group1->get($lable, new Collection())->sum("payment"),
//                    0,
//                    $model_group0->get($lable, new Collection())->sum("payment") - $model_group1->get($lable, new Collection())->sum("payment"),
//                ]);
                $income = $model_group0->get($lable, new Collection())->sum("payment")
                    - $model_group1->get($lable, new Collection())->sum("payment");
                $datas[$key] = round($income, 2);
            }

            $description = "年统计";
        }
//        dd($models,$model_group0,$model_group1,$dates,$datas);
        $lables = $dates;

        return new Box("商城净收入统计图", ChartManager::line($lables, '商城收入', $datas));

//        return $content
//            ->header('商城净收入统计图')
////			->description('折线图')
//            ->row(ChartManager::line($lables, '商城收入', $datas))
//            ->row($this->chartform("/admin/chart/finance/income"));
    }

    protected function chartform($action)
    {
        $form = new Form(new StatisticOrder);
        $form->setTitle("过滤");
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
        $form->select('type', '类型')
            ->options([0 => "每日统计", 2 => "每月统计", 4 => "每年统计"])
            ->default($this->request->filled("type") ? $this->request->get("type") : 2);

        $form->date('date_from', "开始时间")
            ->default(\request('date_from'));
        $form->date('date_to', "结束时间")
            ->default($this->request->get('date_to'));

        return $form;
    }
}
