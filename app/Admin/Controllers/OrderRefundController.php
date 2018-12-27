<?php

namespace App\Admin\Controllers;

use App\Components\NativePalceReagionManager;
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

class OrderRefundController extends Controller
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
            ->header('Index')
            ->description('description')
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
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

        $grid->id('Id');
        $grid->order_id('关联订单id');
        $grid->order_sku_id('关联订单商品id');
        $grid->amount('退款商品数量');
        $grid->reason('退款原因');
        $grid->status('状态');
        $grid->payment('退款金额');
        $grid->note('备注');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
        $show = new Show(OrderRefund::findOrFail($id));

        $show->id('Id');
        $show->order_id('关联订单id');
        $show->order_sku_id('关联订单商品id');
        $show->amount('退款商品数量');
        $show->reason('退款原因');
        $show->status('状态');
        $show->payment('退款金额');
        $show->note('备注');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

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
        $form->number('order_sku_id', '关联订单商品id');
        $form->number('amount', '退款商品数量');
        $form->text('reason', '退款原因');
        $form->number('status', '状态');
        $form->decimal('payment', '退款金额');
        $form->text('note', '备注');

        return $form;
    }

    public function getGrid(Content $content, Request $request)
    {
//        return $request->all();
        return $content
            ->header('Index')
            ->description('description')
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
            ->default(\request('type') | 2);

        $form->switch('with_status_0', "包含未通过申请")
//            ->help(\request('with_status_0')=="on")
            ->default(\request('with_status_0') == "on");
        $form->switch('with_status_4', "包含已驳回申请")
            ->default(\request('with_status_4') == "on");

        $form->date('date_from', "开始时间")
            ->default(\request('date_from'));
        $form->date('date_to', "结束时间")->default(\request('date_to') == "on");

        return $form;
    }

    private static function getModel(Request $request)
    {
        $query = OrderRefund::query();

        if ($request->filled("date_from") && $request->filled("date_to")) {
            $query->where("created_at", ">=", $request->get("date_from"));
            $query->where("created_at", '<=', $request->get("date_to"));
        }

        if ($request->get("with_status_0") == "off")
            $query->where('status', '<>', '0');
        if ($request->get("with_status_4") == "off")
            $query->where('status', '<>', '4');

        $models = $query->get();
        return $models;
    }
}
