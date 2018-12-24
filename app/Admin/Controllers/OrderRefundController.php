<?php

namespace App\Admin\Controllers;

use App\Models\OrderRefund;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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
}
