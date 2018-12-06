<?php

namespace App\Admin\Controllers;

use App\Models\GoodsBenefit;
use App\Http\Controllers\Controller;
use App\Models\GoodsSKU;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodsBenefitController extends Controller
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
		$grid = new Grid(new GoodsBenefit);
		
		$grid->id('Id');
		$grid->sku_id('促销商品')->display(function ($sku_id) {
			$sku = GoodsSKU::find($sku_id);
			$ret = "<span class='label label-error'>商品id错误</span>";
			if ($sku)
				$ret = "<a href='/admin/goods_sku/{$sku_id}'><span class='label label-primary'>{$sku->sku_name}</span></a>";
			return $ret;
		});
		$grid->title('活动标题');
		$grid->desc('活动描述');
		$grid->price('活动价');
		$grid->origin_price('原价');
		$grid->show_origin_price('显示原价');
		$grid->status('活动状态')->display(function ($status) {
			$ret="<label class='label label-danger'>未知的状态</label>";
			switch ($status){
				case '-1':
					$ret="<label class='label label-default'>已结束</label>";
					break;
				case '0':
					$ret="<label class='label label-primary'>未开始</label>";
					break;
				case '1':
					$ret="<label class='label label-success'>进行中</label>";
					break;
			}
			return$ret;
		});
		
		$grid->actions(function ($actions) {
//			$actions->disableEdit();
//			$actions->disableView();
			$actions->disableDelete();
		});
		
		$grid->disableFilter();//筛选
//		$grid->disableCreateButton();//新增
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
		$show = new Show(GoodsBenefit::findOrFail($id));
		
		$show->id('Id');
		$show->sku_id('促销商品id');
		$show->title('活动标题');
		$show->desc('活动描述');
		$show->price('活动价');
		$show->origin_price('原价');
		$show->show_origin_price('显示原价');
		$show->status('活动状态')->using(['-1' => '已解释', '0' => '未开始','1'=>'进行中']);
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
		$form = new Form(new GoodsBenefit);
		
		$form->select('sku_id', '子商品id')
			->options(function ($id) {
				$sku = GoodsSKU::find($id);
				
				if ($sku) {
					return [$sku->id => $sku->sku_name];
				}
			})->ajax('/api/admin/sku/search');
//		$form->display('sku', '子商品id');
		$form->text('title', '活动标题');
		$form->text('desc', '活动描述');
		$form->decimal('price', '活动价');
		$form->decimal('origin_price', '原价');
		$form->decimal('show_origin_price', '显示原价');
		$form->datetimeRange('time_form', 'time_to', '活动时间')->rules('required|after:now');
		$form->switch('reset', '结束时恢复原价')->default(1);
		
		$form->saved(function (Form $form) {
			
			if ($form->model()->time_form <= now() && $form->model()->time_to > now()) {
				$sku = GoodsSKU::find($form->model()->sku_id);
				$sku->price = $form->model()->price;
				$sku->save();
			}
			
		});
		
		return $form;
	}
}
