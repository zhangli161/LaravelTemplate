<?php

namespace App\Admin\Controllers;

use App\Components\ChartManager;
use App\Components\NativePalceReagionManager;
use App\Models\NativePlaceRegion;
use App\Models\Order;
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

class StatisticOrderController extends Controller
{
    use HasResourceActions;

    private $request;
    private $model;

//    public function __construct(Request $request)
//    {
//        $this->type=$request->get("type");
//        $this->region_id=$request->get("region_id");
//        $this->date_from=$request->get("date_from");
//        $this->date_to=$request->get("date_to");
//
//        $this->request = $request;
//
//        $this->model = self::getModel($request);
////        dd($request);
//    }

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index( Request $request)
    {
        $content=new Content();
//        $this->request=$request;
//        dd($request->all());
        return $content
            ->header('订单统计')
            ->row($this->chartform($request,"/admin/statistic/order"))
            ->row($this->grid($request))
            ->row($this->count_chart($content, $request))
            ->row($this->payment_chart($content, $request))
            ->row("<script>disablePjax=true</script>");
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

        $titles = ['时间', '地区', "订单总数", "订单总金额"];

        $region_id = $request->get("region_id", '0');
//            $request->filled("region_id") ? (
//        $request->filled("city") ? $request->get("city") : $request->get("region_id"))
//            : "0";//request中获取或全国
        $region = NativePalceReagionManager::getById($region_id);

//        return dump($region_id);
        $rows = array();
        $type = $request->filled("type") ? $request->get("type") : 2;

//        dd($region_id,$type);
        if ($type == "0") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $dates = getDatesBetween($model->min('created_at'), $model->max('created_at'));
            foreach ($dates as $key => $lable) {
                array_push($rows, new Collection([$lable,
                    $region ? NativePalceReagionManager::getFullAddress($region->region_id) : "全国",
                    $model_group->get($lable, new Collection())->count(),
                    round($model_group->get($lable, new Collection())->sum('payment'), 2)]));
            }

        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
            $dates = getDatesBetween($model->min('created_at'), $model->max('created_at'), 2);
            foreach ($dates as $key => $lable) {
                array_push($rows, new Collection([$lable,
                    $region ? NativePalceReagionManager::getFullAddress($region->region_id) : "全国",
                    $model_group->get($lable, new Collection())->count(),
                    round($model_group->get($lable, new Collection())->sum('payment'), 2)]));
            }
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $dates = getDatesBetween($model->min('created_at'), $model->max('created_at'), 4);
            foreach ($dates as $key => $lable) {
                array_push($rows, new Collection([$lable,
                    $region ? NativePalceReagionManager::getFullAddress($region->region_id) : "全国",
                    $model_group->get($lable, new Collection())->count(),
                    round($model_group->get($lable, new Collection())->sum('payment'), 2)
                ]));
            }
        }
//        dd($model_group,$titles,$dates,$rows);

        return view('admin.table.index', ['titles' => $titles, "rows" => $rows]);
    }

    private static function getModel(Request $request)
    {
        $query = Order::query()->where("status", "5");

        if ($request->filled("date_from") && $request->filled("date_to")) {
            $query->where("created_at", ">=", $request->get("date_from"));
            $query->where("created_at", '<=', $request->get("date_to"));
        }
        $region_id = $request->filled("region_id") ?
            $request->filled("city") ? $request->get("city") : $request->get("region_id")
            : "0";//request中获取或全国
        if ($region_id != "0")
            $query->whereIn("receiver_region_id",
                NativePalceReagionManager::getChildren($region_id)->pluck('region_id')->toArray()
            );
        $model = $query->orderBy('created_at', 'asc')->get();
        return $model;
    }

    public function count_chart(Content $content, Request $request)
    {
        $model = self::getModel($request);
        //如果未获取的数据
        if ($model->count() < 1)
            return new Box('订单数量折线图', "未找到对应数据！");
//            return $content
//                ->header('订单数量折线图')
////			->description('折线图')
//                ->row("未找到对应数据！")
//                ->row($this->chartform("/admin/chart/order/count"));


        $description = "";
        $dates = array();
        $datas = array();
        $type = $request->filled("type") ? $request->get("type") : 2;
        $lables=[];
        if ($type == "0") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'));
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
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 2);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "月统计";
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 4);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->count();
            }

            $description = "年统计";
        }
        return new Box('订单数量折线图', ChartManager::line($lables, '订单数量', $datas, "count"));

//        return $content
//            ->header('订单数量折线图')
//            ->description($description)
//            ->row(ChartManager::line($lables, '订单数量', $datas))
//            ->row($this->chartform("/admin/chart/order/count"));
    }

    public function payment_chart(Content $content, Request $request)
    {
        $model = self::getModel($request);
        //如果未获取的数据
        if ($model->count() < 1)
            return new Box('订单金额折线图', "未找到对应数据");
//
//        return $content
//                ->header('订单金额折线图')
////			->description('折线图')
//                ->row("未找到对应数据！")
//                ->row($this->chartform("/admin/chart/order/payment"));


        $description = "";
        $lables = array();
        $datas = array();
        $type = $request->filled("type") ? $request->get("type") : 2;
        if ($type == "0") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'));
//            $lables=$model->keys()->toArray();
            foreach ($lables as $key => $lable) {
                $datas[$key] = round($model_group->get($lable, new Collection())->sum('payment'), 2);
            }

            $description = "日统计";
        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
//            $lables=$model_group->keys()->toArray();
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 2);
            foreach ($lables as $key => $lable) {
                $datas[$key] = round($model_group->get($lable, new Collection())->sum('payment'), 2);
            }

            $description = "月统计";
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 4);
            foreach ($lables as $key => $lable) {
                $datas[$key] = round($model_group->get($lable, new Collection())->sum('payment'));
            }

            $description = "年统计";
        }
        return new Box('订单金额折线图', ChartManager::line($lables, '订单金额', $datas, "payment"));

//        return $content
//            ->header('订单金额折线图')
////			->description('折线图')
//            ->row(ChartManager::line($lables, '订单金额', $datas))
//            ->row($this->chartform("/admin/chart/order/payment"));
    }

    protected function chartform(Request $request,$action)
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
            ->options(["0" => "每日统计", "2" => "每月统计", "4" => "每年统计"])
            ->default($request->filled("type") ? $request->get("type") : 2);

        $region_ids = NativePalceReagionManager::getProviencesAndCitys();
        $names = [];
        foreach ($region_ids as $region) {
            array_push($names, NativePalceReagionManager::getFullAddress($region->region_id, " "));
        }
        $options = array_combine($region_ids->pluck("region_id")->toArray(), $names);
        $options[0] = "全国";
        ksort($options);


        $form->select('region_id', '地区')
            ->options($options)
            ->default($request->get('region_id','0') );
//            ->load('city', '/api/admin/region/getByParentid');
//        $form->select('city', '市')
//            ->options($options);

        $form->date('date_from', "开始时间")->default(\request('date_from'));
        $form->date('date_to', "结束时间")->default(\request('date_to'));

        $form->hidden('after-save')->default('1');
        $form->saved(function (){
            dd( "aaa");
        });
        $form->saving(function (){
            dd("bbb");
        });
//        $form->text("aaaa","bbb")->default(json_encode($this->request->toArray()));
        return $form;
    }

    public function post(Request $request)
    {
//        return "hahaaha";
//        dd($request->all());
//        exit(1);
        echo ($this->index($request)->render()) ;
        exit(1);
    }
}
