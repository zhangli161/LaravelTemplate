<?php

namespace App\Admin\Controllers;

use App\Models\Coupon;
use App\Models\CouponBenefit;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use function foo\func;

class CouponBenefitController extends Controller
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
        $grid = new Grid(new CouponBenefit);

        $grid->id('Id');
        $grid->coupon_id('关联优惠券')->dispaly(function ($coupon_id) {
            $coupon = Coupon::find($coupon_id);
            return $coupon ? $coupon->name : "优惠券丢失";
        });
        $grid->max_amount('最大领取数量');
        $grid->date_form('开始日期');
        $grid->date_to('结束日期');
        $grid->message_image('对话框样式')->image();
//        $grid->created_at('创建时间');
//        $grid->updated_at('修改时间');

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
        $show = new Show(CouponBenefit::findOrFail($id));

        $show->id('Id');
        $show->coupon_id('关联优惠券')->as(function ($coupon_id) {
            $coupon = Coupon::find($coupon_id);
            return $coupon ? $coupon->name : "优惠券丢失";
        });
        $show->max_amount('最大领取数量');
        $show->date_form('开始日期');
        $show->date_to('结束日期');
        $show->message_image('对话框样式');
        $show->created_at('创建时间');
        $show->updated_at('修改时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CouponBenefit);

        $coupons = Coupon::get();
        $options = $coupons->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        });

        $form->select('coupon_id', '关联优惠券')->options($options)->rules("required");
        $form->number('max_amount', '最大领取数量')->default(1);
        $form->datetime('date_form', '开始日期')->rules("required");
        $form->datetime('date_to', '结束日期')->rules("required");
        $form->image('message_image', '对话框样式')->rules("required|after_now");
        $form->editor("content.content", "活动描述");

        return $form;
    }
}
