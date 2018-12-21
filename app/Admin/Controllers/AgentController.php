<?php
/**
 * Created by PhpStorm.
 * User:ieso
 * Date:2018/12/21
 * Time:11:46
 */

namespace App\Admin\Controllers;

use App\Components\AdminManager;
use App\Components\AgentManager;
use App\Components\ChartManager;
use App\Components\NativePalceReagionManager;
use App\Http\Controllers\Controller;
use App\Models\NativePlaceRegion;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agent;

class AgentController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $admin = Admin::user();
        $agent = Agent::where("admin_id", $admin->id)->with(["region", "users"])->first();
        $request = new Request([
            'date_from' => Carbon::createFromTimestamp(time() - 86400 * 7),
            "date_to" => Carbon::now(),
            "type" => 0
        ]);
        return $content
            ->header('代理商首页')
            ->description('代理商首页')
            ->row(function (Row $row) use ($agent, $request) {

                $row->column(8, function (Column $column) use ($agent, $request) {
                    $column->row($this->grid($agent));
                    $column->row(function (Row $row) use ($agent, $request) {
                        //                    //订单数量表
                        $model2 = self::getOrders($request);
                        $box2 = new Box("近七日粉丝订单量", $this->orderChartView($model2, $request));
                        $box2->collapsable();
                        $box2->removable();
                        $row->column(6, $box2);

                        //粉丝图表
                        $model1 = self::getFans($request);
                        $box1 = new Box("近七日粉丝订单量", $this->funsChartView($model1, $request));
                        $box1->collapsable();
                        $box1->removable();
                        $row->column(6, $box1);
                    });
                });

                $row->column(4, function (Column $column) use ($agent, $request) {
                    $box=new Box('操作','aaaaa');
                    $box->collapsable();
                    $box->removable();
                    $column->row($box);

                    //小程序二维码
                    $html = ($agent->xcx_qr ?
                        "<image src='$agent->xcx_qr' style='width: 100%'/>" :
                        "<div style='width:100%;height:0;padding-bottom: 100%;font-size: large'>您还没有二维码，点击<a href='/admin/agent/getQR/$agent->id'>生成二维码</a></div>");
                    $column->row(new Box('推广二维码',$html));

                });
            });
    }

    public function grid(Agent $agent)
    {
        $orders = AgentManager::getOrders($agent);
        $orders_finish = $orders->where("status", 5);
        $rows = [
            ["name" => "代理商id", "value" => $agent->id],
            ["name" => "真实姓名", "value" => $agent->real_name],
            ["name" => "代理地区", "value" => NativePalceReagionManager::getFullAddress($agent->region->region_id)],
            ["name" => "粉丝人数", "value" => $agent->users->count()],
            ["name" => "返利订单数量", "value" => $orders_finish->count()],
            ["name" => "返利订单金额", "value" => $orders_finish->sum("payment")],
            ["name" => "本日新增粉丝数量", "value" => $agent->users()->whereDate('bind_agent_time', Carbon::today())->count()],
            ["name" => "本周新增粉丝数量", "value" => $agent->users()->whereDate('bind_agent_time', '>=', date('Y-m-d', strtotime('last Monday')))->count()],
            ["name" => "本月新增粉丝数量", "value" => $agent->users()->whereDate('bind_agent_time', date('Y-m-d', strtotime('this month')))->count()],
        ];
        return view("admin.agent.index", ["title" => "供应商", "rows" => $rows]);
    }

    public function funsChart(Content $content, Request $request)
    {
        $model = self::getFans($request);
        //如果未获取的数据
        if ($model->count() < 1)
            return $content
                ->header('粉丝数量折线图')
//			->description('折线图')
                ->row("未找到对应数据！")
                ->row($this->chartForm("/admin/agent/chart/fans"));
        $chart = $this->funsChartView($model, $request);

        return $content
            ->header('粉丝数量折线图')
            ->description("")
            ->row($chart)
            ->row($this->chartForm("/admin/agent/chart/fans"));
    }

    public function funsChartView($model, Request $request)
    {
        $description = "";
        $datas = array();
        $type = $request->filled("type") ? $request->get("type") : 2;
        if ($type == "0") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->bind_agent_time));
            });
            $lables = getDatesBetween($model->min('bind_agent_time'), $model->max('bind_agent_time'));
//            $lables=$model->keys()->toArray();
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "日统计";
        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->bind_agent_time));
            });

//            $lables=$model_group->keys()->toArray();
            $lables = getDatesBetween($model->min('bind_agent_time'), $model->max('bind_agent_time'), 2);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "月统计";
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->bind_agent_time));
            });
            $lables = getDatesBetween($model->min('bind_agent_time'), $model->max('bind_agent_time'), 4);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "年统计";
        }
        return $chart = ChartManager::line($lables, '新增粉丝数量', $datas, 'fans');
    }

    public function orderChart(Content $content, Request $request)
    {
        $model = self::getOrders($request);
        //如果未获取的数据
        if ($model->count() < 1)
            return $content
                ->header('订单数量折线图')
//			->description('折线图')
                ->row("未找到对应数据！")
                ->row($this->chartForm("/admin/agent/chart/order"));
        $chart = $this->orderChartView($model, $request);
        return $content
            ->header('订单数量折线图')
            ->description("")
            ->row($chart)
            ->row($this->chartForm("/admin/agent/chart/order"));
    }

    public function orderChartView($model, Request $request)
    {

        $description = "";
        $datas = array();
        $type = $request->filled("type") ? $request->get("type") : 2;
        if ($type == "0") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->completed_at));
            });
            $lables = getDatesBetween($model->min('completed_at'), $model->max('completed_at'));
//            $lables=$model->keys()->toArray();
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "日统计";
        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->completed_at));
            });

//            $lables=$model_group->keys()->toArray();
            $lables = getDatesBetween($model->min('completed_at'), $model->max('completed_at'), 2);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "月统计";
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->completed_at));
            });
            $lables = getDatesBetween($model->min('completed_at'), $model->max('completed_at'), 4);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "年统计";
        }
        $chart = ChartManager::line($lables, '订单数量', $datas, 'orders');
        return $chart;
    }

    private static function getFans(Request $request)
    {
        $admin = Admin::user();
        $agent = Agent::query()->where("admin_id", $admin->id)->with(["region", "users"])->first();

        $query = $agent->users();

        if ($request->filled("date_from") && $request->filled("date_to")) {
            $query->where("bind_agent_time", ">=", $request->get("date_from"));
            $query->where("bind_agent_time", '<=', $request->get("date_to"));
        }
        $model = $query->orderBy('bind_agent_time')->get();
        return $model;
    }

    private static function getOrders(Request $request)
    {
        $admin = Admin::user();
        $agent = Agent::query()->where("admin_id", $admin->id)->with(["region", "users"])->first();

//        $query = $agent->users();
        $collection = AgentManager::getOrders($agent)->where('status', 5);

//        if ($request->filled("status")) {
////            $collection->where("status", "=", $request->get("status"));
//            $collection->whereIn("status", $request->get("status"));
//        }

        if ($request->filled("date_from") && $request->filled("date_to")) {
//            $collection->where("completed_at", ">=", $request->get("date_from"));
//            $collection->where("completed_at", '<=', $request->get("date_to"));
            $collection = $collection->filter(function ($item, $key) use ($request) {
                return $item->completed_at >= $request->get("date_from")
                    && $item->completed_at <= $request->get("date_to");
            });
        }
        $model = $collection->sortBy('completed_at');
        return $model;
    }

    private function chartForm($action)
    {
        $form = new Form(new Agent());
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
            ->default(\request('type') ? \request('type') : 2);
//        $form->checkbox("status", "只查看可返利订单")
//            ->options([5 => "是"]);
        $form->date('date_from', "开始时间")->default(\request('date_from'));
        $form->date('date_to', "结束时间")->default(\request('date_to'));

        return $form;
    }

    private function fansForm()
    {

    }
}