<?php

namespace App\Admin\Controllers;

use App\Components\AgentManager;
use App\Components\ChartManager;
use App\Components\NativePalceReagionManager;
use App\Models\Agent;
use App\Models\GoodsSKU;
use App\Models\GoodsSPU;
use App\Models\NativePlaceRegion;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\OrderSKU;
use App\Models\StatisticOrder;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StatisticAgentController extends Controller
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
            ->row($this->grid($request))
            ->row($this->gridForm("/admin/statistic/agent"));
    }


    /**
     * Make a grid builder.
     *
     * @return mixed
     */
    protected function grid(Request $request)
    {
        $model = self::getModel($request);

        if ($model->count() < 1) {
            return "未找到数据";
        }

        $titles = ['代理商id', '粉丝总数', "粉丝增长数量", "粉丝消费额", "粉丝退货单数", "查看粉丝增长图表", "查看粉丝消费图表"];

        $rows = array();
        foreach ($model as $agent) {
            array_push($rows, [
                $agent->id,
                $agent->users->count(),
                $agent->increase_users->count(),
                $agent->order_agent->sum("order_payment"),
                $agent->user_refunds->count(),
                "<a href='/admin/chart/agent/fans?agent_id=$agent->id'>查看</a>",
                "<a href='/admin/chart/agent/fans_cost?agent_id=$agent->id'>查看</a>"
            ]);
        }
//        dd($rows);


        return view('admin.table.index', ['titles' => $titles, "rows" => $rows]);
    }

    private static function getModel(Request $request)
    {
        $agents = Agent::with(["users", "order_agent", "finances"])->get();

        if ($request->filled("date_from") && $request->filled("date_to")) {
            foreach ($agents as $agent) {
                $agent->increase_users = $agent->users()
                    ->where("bind_agent_time", ">=", $request->get("date_from"))
                    ->where("bind_agent_time", "<=", $request->get("date_to"))
                    ->get();


                $agent->order_agent = $agent->order_agent()
                    ->where("status", "1")
                    ->where("created_at", ">=", $request->get("date_from"))
                    ->get();

                $order_ids = $agent->order_agent->pluck("order_id")->toArray();
                $agent->user_refunds = OrderRefund::query()->whereIn("order_id", $order_ids)
                    ->get();
            }
        } else {
            foreach ($agents as $agent) {
                $agent->increase_users = $agent->users;
                $agent->order_agent = $agent->order_agent()
                    ->where("status", "1")
                    ->get();
                $order_ids = $agent->order_agent->pluck("order_id")->toArray();
                $agent->user_refunds = OrderRefund::query()->whereIn("order_id", $order_ids)
                    ->get();
            }
        }

        return $agents;
    }

    public function fans_chart(Content $content, Request $request)
    {
        $agent = Agent::with("users")
            ->findOrFail($request->get("agent_id"));
        if ($request->filled("date_from") && $request->filled("date_to")) {
            $model = $agent->users()
                ->where("bind_agent_time", ">=", $request->get("date_from"))
                ->where("bind_agent_time", "<=", $request->get("date_to"))
                ->get();

        } else {
            $model = $agent->users;
        }
//        dd($agent->toArray());
        //如果未获取的数据
        if ($model->count() < 1)
            return $content
                ->header('粉丝增长量折线图')
//			->description('折线图')
                ->row("未找到对应数据！")
                ->row($this->chartform("/admin/chart/agent/fans"));


        $description = "";
        $datas = array();
        $type = $request->filled("type") ? $request->get("type") : 2;
        if ($type == "0") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('bind_agent_time'), $model->max('bind_agent_time'));
//            $lables=$model->keys()->toArray();
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "日统计";
        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
//            $lables=$model_group->keys()->toArray();
            $lables = getDatesBetween($model->min('bind_agent_time'), $model->max('bind_agent_time'), 2);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "月统计";
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('bind_agent_time'), $model->max('bind_agent_time'), 4);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "年统计";
        }
        return $content
            ->header('粉丝增长量折线图')
            ->description($description)
            ->row(ChartManager::line($lables, '粉丝增长量', $datas))
            ->row($this->chartform("/admin/chart/agent/fans"));
    }

    public function fans_cost(Content $content, Request $request)
    {
        $agent = Agent::with("order_agent")
            ->findOrFail($request->get("agent_id"));
        if ($request->filled("date_from") && $request->filled("date_to")) {
            $model = $agent->order_agent()->where("status","1")
                ->where("created_at", ">=", $request->get("date_from"))
                ->where("created_at", "<=", $request->get("date_to"))
                ->get();

        } else {
            $model = $agent->order_agent()->where("status","1")->get();
        }
//        dd($agent->toArray());
        //如果未获取的数据
        if ($model->count() < 1)
            return $content
                ->header('分销订单金额折线图')
//			->description('折线图')
                ->row("未找到对应数据！")
                ->row($this->chartform("/admin/chart/agent/fans_cost"));

        $description = "";
        $datas = array();
        $type = $request->filled("type") ? $request->get("type") : 2;
        if ($type == "0") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'));
//            $lables=$model->keys()->toArray();
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->sum("order_payment");
            }

            $description = "日统计";
        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
//            $lables=$model_group->keys()->toArray();
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 2);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->sum("order_payment");
            }

            $description = "月统计";
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 4);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->sum("order_payment");
            }

            $description = "年统计";
        }
        return $content
            ->header('分销订单金额折线图')
            ->description($description)
            ->row(ChartManager::line($lables, '订单金额', $datas))
            ->row($this->chartform("/admin/chart/agent/fans_cost"));
    }

    protected function gridForm($action)
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
//        $form->select('type', '类型')
//            ->options([0 => "每日统计", 2 => "每月统计", 4 => "每年统计"])
//            ->default(\request('type') | 2);

        $form->date('date_from', "开始时间")->default(\request('date_from'));
        $form->date('date_to', "结束时间")->default(\request('date_to'));

        return $form;
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
        $form->number("agent_id", "分销商id")->default(\request("agent_id"));
        $form->select('type', '类型')
            ->options([0 => "每日统计", 2 => "每月统计", 4 => "每年统计"])
            ->default(\request('type') | 2);

        $form->date('date_from', "开始时间")->default(\request('date_from'));
        $form->date('date_to', "结束时间")->default(\request('date_to'));

        return $form;
    }
}
