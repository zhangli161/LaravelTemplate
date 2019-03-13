<?php

namespace App\Admin\Controllers;

use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CouponController extends Controller
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
			->header('优惠券')
			->description('优惠券')
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
			->header('优惠券')
			->description('优惠券')
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
			->header('优惠券')
			->description('优惠券')
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
		$grid = new Grid(new Coupon);
        $grid->model()->orderBy("created_at","desc");

        $grid->id('Id');
		$grid->name('名称');
		$types = [
			1 => '打折券',
			2 => '代金券',
		];
		$grid->type('类型')->using($types);
		$grid->value('打折比例/代金券金额');
		$grid->min_cost('门槛金额');
		$grid->expiry_date('固定有效期');
		$grid->expriy_days('动态有效期');
		$grid->created_at('创建时间');
		$grid->updated_at('上次修改时间');

		$grid->disableExport();
        $grid->actions(function ($actions) {
            $actions->disableDelete();
//            $actions->disableEdit();
//            $actions->disableView();
        });
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
		$show = new Show(Coupon::findOrFail($id));
		
		$show->id('Id');
		$show->name('名称');
		$types = [
			1 => '打折券',
			2 => '代金券',
		];
		$show->type('类型')->using($types);
		$show->value('打折比例/代金券金额');
		$show->min_cost('门槛金额');
		$show->expiry_date('固定有效期');
		$show->expriy_days('动态有效期');
		$show->created_at('创建时间');
		$show->updated_at('上次修改时间');
		$show->distribute_methods('发放方式', function ($grid) {
			$grid->method('发放方式')->using([0=>"失效",1 => '积分兑换']);
			$grid->price('价格');
			
			$grid->disableFilter();//筛选
			$grid->disableCreateButton();//新增
			$grid->disableExport();//导出
			
			$grid->disableActions();//行操作
			$grid->disableRowSelector();//CheckBox
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
		$form = new Form(new Coupon);
		
		$form->tab('基本信息', function ($form) {
			$form->text('name', '优惠券名称')->rules('required');
			$types = [
				1 => '打折券',
				2 => '代金券',
			];
			$form->select('type', '类型')->options($types)->rules('required');
			$form->decimal('value', '打折比例/代金券金额')
				->help('打折券请填写0-1之间的小数，代金券请填写代金券金额')->rules('required');
			$form->decimal('min_cost', '门槛金额')->rules('required');
			$form->date('expiry_date', '固定有效期')
//	        ->default(date('Y-m-d'))
				->help('非必填。优惠券在该日的23:59后失效。最终有效期会取最小值');
			$form->number('expriy_days', '动态有效期')
				->help('非必填。优惠券在获得n天后的23:59后失效。最终有效期会取最小值');
//		})->tab('关联商品', function ($form) {
//
//			$form->hasMany('skus', '适用商品(不添加则为全场通用)', function (Form\NestedForm $form) {
//
//			});
		
		})->tab('发放方式', function ($form) {
			$form->hasMany('distribute_methods', '发放方式', function (Form\NestedForm $form) {
				$form->select('method', '发放方式')->options([0=>"失效",1 => '积分兑换'])->default(1);
				$form->decimal('price', '价格');
				$form->switch('send_message', '发送系统消息');
				$form->number('stock', '库存限制')->help('-1为无限');
//				$form->number('limit_per_user', '用户个人领取数量上限')->help('-1为无限');
//				$form->number('cooldown', '用户个人领取间隔')->help('单位小时。0不限制');
			});
		});
		
		return $form;
	}
}
