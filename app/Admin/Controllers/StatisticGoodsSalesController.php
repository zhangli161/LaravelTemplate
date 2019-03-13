<?php

namespace App\Admin\Controllers;

use App\Components\ChartManager;
use App\Components\NativePalceReagionManager;
use App\Models\GoodsSKU;
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
use Encore\Admin\Widgets\Box;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StatisticGoodsSalesController extends Controller
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
            ->header('商品销售统计')
            ->description('商品销售统计')
            ->row($this->chartform("/admin/statistic/good-sales"))
            ->row($this->grid($request))
            ->row("<script>disablePjax=true</script>");
//            ->row($this->count_chart($content,$request))
//            ->row($this->payment_chart($content,$request));
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

        $titles = ['Article code(SKU)', 'Article name',
            'Description', "Quantity",'Unit','Unit Price','Total Price',
            "Actual Unit Pric", 'Actual Total Price', 'Quantity of refunded goods', 'Refund amount'];

        $region_id = $request->filled("provience") ?
            $request->filled("city") ? $request->get("city") : $request->get("provience")
            : "0";//request中获取或全国
        $region = NativePalceReagionManager::getById($region_id);

        $rows = array();

        $model = $model->groupBy('sku_id');

        foreach ($model as $sku_id => $datas) {
            $sku = GoodsSKU::query()->find($sku_id);
            $datas = $datas->groupBy('average_price');
            foreach ($datas as $price => $orderskus) {
                $refund_amount = 0;
                foreach ($orderskus as $ordersku) {
                    $refund_amount += $ordersku->refund->whereIn('status', [1, 2, 3])->sum("payment");
                }
                array_push($rows, [
                    $sku ? $sku->sku_no : "商品丢失",//'商品编号(SKU)',
                    $sku ? $sku->sku_name : "商品丢失",// '品名'
                    $sku ? implode(',',$sku->spec_value_strs) : "商品丢失",// '规格'
                    $orderskus->sum('amount'),//"销量",
                    "PC",
                    $orderskus->first()->price,//原价
                    $orderskus->sum('amount')* $orderskus->first()->price,//"原价总价",
                    $price,//"实际金额",
                    $orderskus->sum('total_price'),//'销售额',
                    $orderskus->sum('refund_amount'),//'退款数量',
                    $refund_amount,//'退款金额'
                ]);
            }
        }

        return view('admin.table.index', ['titles' => $titles, "rows" => $rows]);
    }

    private static function getModel(Request $request)
    {
        $query = Order::query()->where("status", "5");

        if ($request->filled("date_from") && $request->filled("date_to")) {
            $query->whereDate("created_at", ">=", $request->get("date_from"));
            $query->whereDate("created_at", '<=', $request->get("date_to"));
        }
//        $region_id = $request->filled("provience") ?
//            $request->filled("city") ? $request->get("city") : $request->get("provience")
//            : "0";//request中获取或全国
//        if ($region_id != "0")
//            $query->whereIn("receiver_region_id",
//                NativePalceReagionManager::getChildren($region_id)->pluck('region_id')->toArray()
//            );
        $order_ids = $query->pluck('id');
        $model = OrderSKU::with('refund')->whereIn('order_id', $order_ids)->get();
        return $model;
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
//        $form->select('type', '类型')
//            ->options(["0" => "每日统计", "2" => "每月统计", "4" => "每年统计"])
//            ->default($this->request->filled("type")?$this->request->get("type"):2);
//
//        $proviences = NativePalceReagionManager::getProviencesAndCitys();
//        $names = [];
//        foreach ($proviences as $region) {
//            array_push($names, NativePalceReagionManager::getFullAddress($region->region_id, " "));
//        }
//        $options = array_combine($proviences->pluck("region_id")->toArray(), $names);
//        $options[0] = "全国";
//        ksort($options);
//
//
//        $form->select('provience', '地区')
//            ->options($options)
//            ->default(\request('provience') | 0);
////            ->load('city', '/api/admin/region/getByParentid');
////        $form->select('city', '市')
////            ->options($options);

        $form->date('date_from', "开始时间")->default(\request('date_from'));
        $form->date('date_to', "结束时间")->default(\request('date_to'));

//        $form->text("aaaa","bbb")->default(json_encode($this->request->toArray()));
        return $form;
    }
}
