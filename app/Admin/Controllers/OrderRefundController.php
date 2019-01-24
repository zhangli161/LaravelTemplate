<?php

namespace App\Admin\Controllers;

use App\Components\NativePalceReagionManager;
use App\Components\OrderManager;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Http\Controllers\Controller;
use App\Models\StatisticOrder;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

class OrderRefundController extends Controller
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
    public function index(Content $content)
    {
        return $content
            ->header('订单退款')
            ->description('订单退款')
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
            ->header('订单退款')
            ->description('订单退款')
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
            ->header('订单退款')
            ->description('订单退款')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('创建')
            ->description('')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderRefund);
        $grid->model()->orderBy("created_at", "desc");

        $grid->filter(function ($filter) {
            $filter->equal('status', '状态')->select([
                0 => "未处理 ",
                1 => " 通过",
//                2 => "退款中",
//                3 => " 退款完成",
                4 => "驳回"
            ]);
        });
        $grid->id('Id');
        $grid->order_id('关联订单id');
        $grid->column("order.id", '订单所有者')
            ->display(function ($order_id) {
//            dd($order_id);
            $order = Order::with("user")->find($order_id);
            if ($order==null)
                return"订单丢失";
            if ($order->user==null)
                return"用户丢失";
            $user=$order->user;
            return "<a href='/admin/users/$user->id'>$user->name</a>";
        })
        ;
        $grid->amount('退款商品数量');
        $grid->reason('退款原因');
        $grid->status('状态')->using([
            0 => "未处理 ",
            1 => " 通过",
            2 => "退款中",
            3 => " 退款完成",
            4 => "驳回"
        ]);
        $grid->payment('退款金额');
        $grid->note('备注');
        $grid->created_at('创建时间');
        $grid->updated_at('上次修改时间');

        $grid->disableExport();
        $grid->disableCreateButton();
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
        $refund = OrderRefund::findOrFail($id);
        $show = new Show($refund);

        $show->id('Id');
        $show->order_id('关联订单id');
        $show->order_sku_id('关联订单商品id');
        $show->amount('退款商品数量');
        $show->reason('退款原因');
        $show->desc('描述');
        $show->status('状态')->using([
            0 => "未处理 ",
            1 => " 通过",
            2 => "退款中",
            3 => " 退款完成",
            4 => "驳回"
        ]);
        $show->payment('退款金额');

        $show->field("albums", "图片")->as(function () use ($refund) {
            if ($refund->albums == null)
                return "无";
            else {
                $html = "";
                foreach ($refund->albums as $album)
                    $html = $html . "<image src='$album'></image>";
                return $html;
            }
        })->unescape();
//        $show->albums("图片", function ($albums) {
//            $html="";
//            foreach ($albums as $album)
//                $html=$html."<image src='$album'></image>";
//            return $html;
//        });
        $show->note('备注', function ($note) {
            return json_encode($note);
        });

        $show->result('退款结果');

        $show->order_skus('订单内商品', function ($order_skus) {
            $order_skus->setResource('');

            $order_skus->sku_name("商品名称");
            $order_skus->thumb("商品图片")->image();
            $order_skus->amount("数量");
            $order_skus->total_price("总价");
        });

        $show->created_at('创建时间');
        $show->updated_at('上次修改时间');


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OrderRefund);

        $form->text('order_id', '关联订单id')->readOnly();
        $form->text('order_sku_id', '关联订单商品id')->readOnly();
        $form->text('amount', '退款商品数量')->readOnly();
        $form->text('reason', '退款原因')->readOnly();
        $form->text('desc', '描述')->readOnly();
        $form->select('status', '状态')->options([
//            0 => "未处理 ",
            1 => " 通过",
//            2 => "退款中",
//            3 => " 退款完成",
            4 => "驳回"
        ])->rules('required');
        $form->decimal('payment', '退款金额')->rules('required');
        $form->text('note', '备注');

        $form->saved(function (Form $form) {
            if ($form->model()->status == 1) {
                //执行退款
                OrderManager::doRefund($form->model());
            };
        });
        return $form;
    }

    public function getGrid(Content $content, Request $request)
    {
//        return $request->all();
        return $content
            ->header('Index')
            ->description('')
            ->row($this->statistic_grid($request))
            ->row($this->chartform("/admin/statistic/refund"));
    }

    public function statistic_grid(Request $request)
    {
        $model = self::getModel($request);

        if ($model->count() < 1) {
            return "未找到数据";
        }

        $titles = ['时间', '退货单数', "退货商品数", "退货总金额"];

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
                array_push($rows, [$lable,
                        $model_group->get($lable, new Collection())->count(),
                        $model_group->get($lable, new Collection())->sum('amount'),
                        round($model_group->get($lable, new Collection())->sum('payment'), 2)]
                );
            }

        } elseif ($type == "2") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y-m", strtotime($item->created_at));
            });
            $dates = getDatesBetween($model->min('created_at'), $model->max('created_at'), 2);
            foreach ($dates as $key => $lable) {
                array_push($rows, [$lable,
                        $model_group->get($lable, new Collection())->count(),
                        $model_group->get($lable, new Collection())->sum('amount'),
                        round($model_group->get($lable, new Collection())->sum('payment'), 2)]
                );
            }
        } elseif ($type == "4") {
            $model_group = $model->groupBy(function ($item) {
                return date("Y", strtotime($item->created_at));
            });
            $dates = getDatesBetween($model->min('created_at'), $model->max('created_at'), 4);
            foreach ($dates as $key => $lable) {
                array_push($rows, [$lable,
                        $model_group->get($lable, new Collection())->count(),
                        $model_group->get($lable, new Collection())->sum('amount'),
                        round($model_group->get($lable, new Collection())->sum('payment'), 2)]
                );
            }
        }

        return view('admin.table.index', ['titles' => $titles, "rows" => $rows]);
    }

    protected function chartform($action)
    {
        $form = new Form(new StatisticOrder());
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

        $form->switch('with_status_0', "包含未通过申请")
//            ->help(\request('with_status_0')=="on")
            ->default(\request('with_status_0') == "on");
        $form->switch('with_status_4', "包含已驳回申请")
            ->default(\request('with_status_4') == "on");

        $form->date('date_from', "开始时间")
            ->default($this->request->get('date_from'));
        $form->date('date_to', "结束时间")
            ->default($this->request->get('date_to'));

        return $form;
    }

    private static function getModel(Request $request)
    {
        $query = OrderRefund::query();

        if ($request->filled("date_from")) {
            $query->where("created_at", ">=", $request->get("date_from"));
        }
        if ($request->filled("date_to"))
            $query->where("created_at", '<=', $request->get("date_to"));

        if ($request->get("with_status_0") == "off")
            $query->where('status', '<>', '0');
        if ($request->get("with_status_4") == "off")
            $query->where('status', '<>', '4');

        $models = $query->get();
        return $models;
    }
}
