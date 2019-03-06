<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\Fahuodan;
use App\Components\ChartManager;
use App\Components\GoodsSKUManager;
use App\Components\NativePalceReagionManager;
use App\Components\PostageMananger;
use App\Models\GoodsSKU;
use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Models\OrderRefund;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;

class OrderController extends Controller
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
        return $content
            ->header('订单')
            ->description('订单')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('订单')
            ->description('订单')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('订单')
            ->description('订单')
            ->body($this->form()->edit($id));
    }

//    /**
//     * Create interface.
//     *
//     * @param Content $content
//     * @return Content
//     */
//    public function create(Content $content)
//    {
//        return $content
//            ->header('创建')
//            ->description('')
//            ->body($this->form());
//    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);
        $grid->model()->orderBy("created_at", "desc");

        $grid->id('订单编号')->sortable();
        $grid->payment('商品实付金额');
        $grid->payment_type('支付方式')->using([1 => '在线支付', 2 => '货到付款']);
        $grid->post_fee('快递费用');
        $grid->status('订单状态')->display(function ($status) {

            return [
                1 => "<lable class='label label-default'>未付款</lable>",
                2 => "<lable class='label label-info'>已付款</lable>",
                3 => "<lable class='label label-warning'>未发货</lable>",
                4 => "<lable class='label label-primary'>已发货</lable>",
                5 => "<lable class='label label-success'>交易成功</lable>",
                6 => "<lable class='label label-danger'>交易关闭</lable>",
            ][$status];
        })->sortable();
//        $grid->paid_at('支付时间');
//        $grid->consigned_at('发货时间');
//        $grid->completed_at('确认收货时间');
//        $grid->closed_at('交易关闭时间');
//		$grid->user_id('用户id');
        $grid->user_id('用户名')->display(function ($user_id) {
            $user = User::find($user_id);
//	        $count = count($comments);
//	        $user=json_encode($user);
            return "<a class='label label-warning' href='/admin/users/{$user['id']}'>{$user['name']}</a>";
        });
//        $grid->receiver_name('收货人姓名');
//        $grid->receiver_phone('收货人电话');
//        $grid->receiver_region_id('地区代码');
//        $grid->receiver_address('详细地址');
//        $grid->buyer_message('买家留言');
//        $grid->buyer_nick('买家昵称');
        $grid->wuliu("快递处理状态")->display(function ($postage) {
            return $postage ?
                "<lable class='label label-success'>是</lable>" :
                "<lable class='label label-danger'>否</lable>";
        });
        $grid->created_at('创建时间')->sortable();
        $grid->updated_at('上次修改时间')->sortable();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
//			$actions->disableEdit();
//			$actions->disableView();
        });
        $grid->disableExport();
        $grid->filter(function ($filter) {
            // 设置created_at字段的范围查询
            $filter->between('created_at', '下单时间')->datetime();

            $filter->equal('status', "订单状态")->select([
                1 => "未付款 ",
                2 => "已付款 ",
//				3 => "未发货 ",
                4 => "已发货 ",
                5 => "交易成功 ",
                6 => "交易关闭	",
            ]);
            // 关联关系查询
            $filter->scope('is_posted', "付款未发货订单")->where('status', 2)->doesntHave('wuliu');
        });

//		$grid->disableFilter();//筛选
        $grid->disableCreateButton();//新增
        $grid->disableExport();//导出

//			$grid->disableActions();//行操作
        $grid->disableRowSelector();//CheckBox
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $order = Order::findOrFail($id);
        $show = new Show($order);
        $export_url = url("/admin/export/order?id=$id");
        $show->panel()->tools(function (Show\Tools $tools) use ($export_url) {
//            $tools->prepend("<a href=\"/admin/export/order?id=$id\" class=\"btn btn-sm btn-primary\" title=\"导出\">
//        <i class=\"fa fa-edit\"></i><span class=\"hidden-xs\"> 编辑</span>
//    </a>");

            $tools->prepend("<button onclick='window.location.href=\"$export_url\"' type=\"button\" class=\"btn btn-sm btn-success grid-export\" id=\"generate-excel\"><i class=\"fa fa-file-excel-o\" aria-hidden=\"true\"></i>导出发货单</button>");
        });


        $show->id('订单编号');
        $show->payment('支付金额')->as(function ($fee) {
            return "￥" . ($fee);
        });
        $show->payment_type('支付方式');
        $show->post_fee('邮费');
        $show->status('状态');
        $show->paid_at('支付时间');
        $show->consigned_at('发货时间');
        $show->completed_at('确认收货时间');
        $show->closed_at('交易关闭时间');
        $show->user_id('用户id');
        $show->receiver_name('收货人姓名');
        $show->receiver_phone('收货人电话');
        $show->receiver_region_id('地区代码');
        $show->receiver_address('收货地址')->unescape()->as(function () use ($order) {
            $address = NativePalceReagionManager::getFullAddress($order->receiver_region_id) . "  $order->receiver_address";
            $html = "
<input onclick=\"$(this).select();document.execCommand('copy');alert('复制成功');\"value='$address' style='border: none;width: 100%' readonly/>
<script>
function copyText(item) {
  item.select();
  document.execCommand(\"copy\"); // 执行浏览器复制命令
  alert(\"复制成功\");
}
</script>";
            return $html;
        });
        $show->buyer_message('买家留言');
        $show->buyer_nick('买家昵称');
        $show->field("name", "快递信息")->as(function () use ($order) {
            $address = NativePalceReagionManager::getFullAddress($order->receiver_region_id) . "  $order->receiver_address";
            $html = "<div>收货人姓名:$order->receiver_name</div>
<div>收货地址:$address</div>
<div>电话:$order->receiver_phone</div>";
            return $html;
        })->unescape();
        $show->note('商家备注');
        $show->created_at('创建时间');
//        $show->updated_at('上次修改时间');
        $show->skus("订单商品", function ($sku) {
            $sku->disableCreateButton();//新增
            $sku->disableExport();//导出
            $sku->disableRowSelector();//CheckBox


//            $sku->id("订单商品id");
            $sku->sku_id("商品id");
            $sku->column("sku.sku_no", "SKU编号");

            $sku->sku_name("商品名称");

            $sku->column("sku.id", "规格")->display(function ($id) {
                $sku = GoodsSKU::find($id);
                if (empty($sku)) {
                    return "商品丢失";
                } else {
                    $strs = GoodsSKUManager::getSpecValuesStr($sku)->spec_value_strs;
                    $html = "";
                    foreach ($strs as $str)
                        $html .= "<div>$str</div>";
                    return $html;
                }
            });

//            $sku->id("订单商品id");
//            $sku->column("refund","退款信息")->display(function ($refund) {
            $sku->id("退款信息")->display(function ($order_sku_id) {

//                dd($refund);
                return OrderRefund::where('order_sku_id',$order_sku_id)->exists() ? '<lable class="label label-success">无</lable>' :
                    '<a class="label label-danger" href="/admin/refund/order?order_sku_id='.$order_sku_id.'">有</a>';
            });
            $sku->thumb("商品图片")->lightbox(["width" => 200]);
            $sku->amount("数量");
            $sku->price("商品价格");
            $sku->total_price("支付金额");
            $sku->average_price("支付单价");
        });
        $show->wuliu('物流信息', function ($show) {
            $show->postage_name("快递名称")->using(PostageMananger::$codes);

            $show->postage_code("快递单号");
            $show->status("状态")->using([
                "0" => "无信息",
                "1" => "运输中",
                "2" => "已收货",
            ]);
            $show->updated_at("物流更新时间");

            $show->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
        });
        $show->xcx_pay('支付信息', function ($show) {
            $show->total_fee("支付金额")->as(function ($fee) {
                return "￥ " . ($fee / 100);
            });
            $show->out_trade_no("微信外部订单号");
            $show->trade_state("订单状态");
            $show->trade_state_desc("描述");
            $show->note("备注");

            $show->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });
        });


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);

        $form->decimal('payment', '实际支付金额');
//        $form->switch('payment_type', '支付方式')->default(1);
//        $form->decimal('post_fee', '邮费');
        $form->select('status', '订单状态')->default(4)->
        options([
            1 => "未付款 ",
            2 => "已付款 ",
//				3 => "未发货 ",
            4 => "已发货 ",
            5 => "交易成功 ",
            6 => "交易关闭	",
        ]);
//        $form->datetime('paid_at', '支付时间')->default(date('Y-m-d H:i:s'));
        $form->datetime('consigned_at', '发货时间')->default(date('Y-m-d H:i:s'));
//        $form->datetime('completed_at', '确认收货时间')->default(date('Y-m-d H:i:s'));
//        $form->datetime('closed_at', '交易关闭时间')->default(date('Y-m-d H:i:s'));
//        $form->number('user_id', '用户id');
//        $form->text('receiver_name', '收货人姓名');
//        $form->text('receiver_phone', '收货人电话');
//        $form->number('receiver_region_id', '地区代码');
//        $form->text('receiver_address', '详细地址');
//        $form->text('buyer_message', '买家留言');
//        $form->text('buyer_nick', '买家昵称');

        $form->select("wuliu.postage_name", '快递名称')->options(PostageMananger::$codes);
        $form->text("wuliu.postage_code", '快递单号');
        $form->text('note', '商家备注');
        return $form;
    }

    public function chart(Content $content)
    {
        $data = [

            "labels" => ["January", "February", "March", "April", "May", "June", "July"],
            "datasets" => [
                [
                    "label" => "My First dataset",
                    "fillColor" => "rgba(220,220,220,0.5)",
                    "strokeColor" => "rgba(220,220,220,0.8)",
                    "highlightFill" => "rgba(220,220,220,0.75)",
                    "highlightStroke" => "rgba(220,220,220,1)",
                    "data" => [65, 59, 80, 81, 56, 55, 40]
                ], [
                    "label" => "My Second dataset",
                    "fillColor" => "rgba(151, 187, 205, 0.5)",
                    "strokeColor" => "rgba(151, 187, 205, 0.8)",
                    "highlightFill" => "rgba(151, 187, 205, 0.75)",
                    "highlightStroke" => "rgba(151,187,205,1)",
                    "data" => [28, 48, 40, 19, 86, 27, 90]
                ]]
        ];
        $options = [
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            "scaleBeginAtZero" => true,

            //Boolean - Whether grid lines are shown across the chart
            "scaleShowGridLines" => true,

            //String - Colour of the grid lines
            "scaleGridLineColor" => "rgba(0,0,0,.05)",

            //Number - Width of the grid lines
            "scaleGridLineWidth" => 1,

            //Boolean - Whether to show horizontal lines (except X axis)
            "scaleShowHorizontalLines" => true,

            //Boolean - Whether to show vertical lines (except Y axis)
            "scaleShowVerticalLines" => true,

            //Boolean - If there is a stroke on each bar
            "barShowStroke" => true,

            //Number - Pixel width of the bar stroke
            "barStrokeWidth" => 2,

            //Number - Spacing between each of the X value sets
            "barValueSpacing" => 5,

            //Number - Spacing between data sets within X values
            "barDatasetSpacing" => 1,

            //String - A legend template
            // {{--legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"--}}

        ];
        return $content
            ->header('Chartjs')
            ->body(new Box('Bar chart',
                ChartManager::newChart("line", $data, $options
                )));
    }
}
