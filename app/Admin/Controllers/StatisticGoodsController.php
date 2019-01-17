<?php

namespace App\Admin\Controllers;

use App\Components\ChartManager;
use App\Components\NativePalceReagionManager;
use App\Models\GoodsSKU;
use App\Models\GoodsSPU;
use App\Models\NativePlaceRegion;
use App\Models\Order;
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

class StatisticGoodsController extends Controller
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
            ->header('商品统计')
//            ->description('')
            ->row($this->grid($request))
            ->row($this->chartform("/admin/statistic/goods"));
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

        $titles = ['时间', '地区', "商品总销量", "商品总销售额"];

        $region_id = $request->filled("provience") ?
            $request->filled("city") ? $request->get("city") : $request->get("provience")
            : "0";//request中获取或全国
        $region = NativePalceReagionManager::getById($region_id);

        $rows = array();
        $type = $request->filled("type") ? $request->get("type") : 2;
        if ($type == "0") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $dates = getDatesBetween($model->min('created_at'), $model->max('created_at'));
            foreach ($dates as $key => $lable) {
                array_push($rows, [$lable, $region ? NativePalceReagionManager::getFullAddress($region->region_id) : "全国", $model_group->get($lable, new Collection())->sum('amount'), round($model_group->get($lable, new Collection())->sum('total_price'), 2)]);
            }

        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
            $dates = getDatesBetween($model->min('created_at'), $model->max('created_at'), 2);
            foreach ($dates as $key => $lable) {
                array_push($rows, [$lable, $region ? NativePalceReagionManager::getFullAddress($region->region_id) : "全国", $model_group->get($lable, new Collection())->sum('amount'), round($model_group->get($lable, new Collection())->sum('total_price'), 2)]);
            }
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $dates = getDatesBetween($model->min('created_at'), $model->max('created_at'), 4);
            foreach ($dates as $key => $lable) {
                array_push($rows, [$lable, $region ? NativePalceReagionManager::getFullAddress($region->region_id) : "全国", $model_group->get($lable, new Collection())->sum('amount'), round($model_group->get($lable, new Collection())->sum('total_price'), 2)]);
            }
        }

        return view('admin.table.index', ['titles' => $titles, "rows" => $rows]);
    }

    private static function getModel(Request $request)
    {
        $query = Order::query();

        if ($request->filled("date_from") && $request->filled("date_to")) {
            $query->where("completed_at", ">=", $request->get("date_from"));
            $query->where("completed_at", '<=', $request->get("date_to"));
        }
        $region_id = $request->filled("provience") ?
            $request->filled("city") ? $request->get("city") : $request->get("provience")
            : "0";//request中获取或全国
        if ($region_id != "0")
            $query->whereIn("receiver_region_id",
                NativePalceReagionManager::getChildren($region_id)->pluck('region_id')->toArray()
            );
        $order_ids = $query->where('status', '5')->pluck('id');
        $model = OrderSKU::query()->whereIn("order_id", $order_ids);
        if ($request->filled('sku_id'))
            $model->where("sku_id", $request->get('sku_id'));
        if ($request->filled('spu_id')) {
            $spu = GoodsSPU::find($request->get('spu_id'));
            $sku_ids = $spu->skus()->pluck("id");
            $model->whereIn("sku_id", $sku_ids);
        }

        $skus = $model->get();
        return $skus;
    }

    public function count_chart(Content $content, Request $request)
    {
        $model = self::getModel($request);
        //如果未获取的数据
        if ($model->count() < 1)
            return $content
                ->header('商品销量统计图')
//			->description('折线图')
                ->row("未找到对应数据！")
                ->row($this->chartform("/admin/chart/goods/payment"));


        $description = "";
        $dates = array();
        $datas = array();
        $type = $request->filled("type") ? $request->get("type") : 2;
        if ($type == "0") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m-d", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'));
//            $lables=$model->keys()->toArray();
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->sum('amount');
            }

            $description = "日统计";
        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
//            $lables=$model_group->keys()->toArray();
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 2);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->sum('amount');
            }

            $description = "月统计";
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 4);
            foreach ($lables as $key => $lable) {
                $datas[$key] = $model_group->get($lable, new Collection())->sum('amount');
            }

            $description = "年统计";
        }
        return $content
            ->header('商品销量统计图')
            ->description($description)
            ->row(ChartManager::line($lables, '商品销量', $datas))
            ->row($this->chartform("/admin/chart/goods/count"));
    }

    public function payment_chart(Content $content, Request $request)
    {
        $model = self::getModel($request);
        //如果未获取的数据
        if ($model->count() < 1)
            return $content
                ->header('商品销售额统计图')
//			->description('折线图')
                ->row("未找到对应数据！")
                ->row($this->chartform("/admin/chart/goods/payment"));


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
                $datas[$key] = round($model_group->get($lable, new Collection())->sum('total_price'), 2);
            }

            $description = "日统计";
        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
//            $lables=$model_group->keys()->toArray();
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 2);
            foreach ($lables as $key => $lable) {
                $datas[$key] = round($model_group->get($lable, new Collection())->sum('total_price'), 2);
            }

            $description = "月统计";
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $lables = getDatesBetween($model->min('created_at'), $model->max('created_at'), 4);
            foreach ($lables as $key => $lable) {
                $datas[$key] = round($model_group->get($lable, new Collection())->sum('total_price'));
            }

            $description = "年统计";
        }

        return $content
            ->header('商品销售额统计图')
//			->description('折线图')
            ->row(ChartManager::line($lables, '商品销售额', $datas))
            ->row($this->chartform("/admin/chart/goods/payment"));
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
            ->default(\request('type') | 2);

        $proviences = NativePalceReagionManager::getProviencesAndCitys();
        $names = [];
        foreach ($proviences as $region) {
            array_push($names, NativePalceReagionManager::getFullAddress($region->region_id, " "));
        }
        $options = array_combine($proviences->pluck("region_id")->toArray(), $names);
        $options[0] = "全国";
        ksort($options);


        $form->select('provience', '地区')
            ->options($options)
            ->default(\request('provience') | 0);
//            ->load('city', '/api/admin/region/getByParentid');
//        $form->select('city', '市')
//            ->options($options);

        $form->text('sku_id', "指定sku_id")->default(\request('sku_id'));
        $form->text('spu_id', "指定spu_id")->default(\request('spu_id'));


        $form->date('date_from', "开始时间")->default(\request('date_from'));
        $form->date('date_to', "结束时间")->default(\request('date_to'));

        return $form;
    }
}
