<?php

namespace App\Admin\Controllers;

use App\Models\GoodsSKU;
use App\Http\Controllers\Controller;
use App\Models\GoodsSpec;
use App\Models\GoodsSpecValue;
use App\Models\Postage;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class GoodsSKUController extends Controller
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
		$grid = new Grid(new GoodsSKU);
		$spu_id = request('spu_id');
		if ($spu_id)
			$grid->model()->where('spu_id', '=', $spu_id);
		
		$grid->disableFilter();//去掉过滤器
		$grid->id('Id');
		$grid->sku_no('Sku编号');
		$grid->sku_name('子商品名称');
		$grid->price('价格（元）');
		$grid->stock('库存量');
//        $grid->shop_id('Shop id');
//        $grid->spu_id('Spu id');
//        $grid->stock_type('减库存时间');
//        $grid->postage('是否包邮');
		$grid->order('排序');
		$grid->created_at('创建时间');
		$grid->updated_at('更新时间');
//        $grid->deleted_at('Deleted at');
		
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
		$show = new Show(GoodsSKU::findOrFail($id));
		
		$show->id('Id');
		$show->sku_no('Sku编号');
		$show->sku_name('子商品名称');
		$show->price('价格');
		$show->stock('库存');
//        $show->shop_id('Shop id');
		$show->spu_id('商品Spu id');
		$show->stock_type('减库存时间');
		$show->postage('是否包邮');
		$show->order('排序');
		$show->created_at('创建时间');
		$show->updated_at('更新时间');
//        $show->deleted_at('Deleted at');
		$show->sku_spec_values('规格', function ($grid) {
			$grid->spec_id('规格名称')->display(function($spec_id) {
				return GoodsSpec::find($spec_id)->spec_name;
			});
			$grid->spec_value_id('规格值')->display(function($spec_value_id) {
				return GoodsSpecValue::find($spec_value_id)->value;
			});
			//行
			$grid->actions(function ($actions) {
				$actions->disableDelete();
				$actions->disableEdit();
				$actions->disableView();
				// 当前行的数据数组
				$spec_id=$actions->row['spec_id'];
				
				// 获取当前行主键值
				$actions->getKey();
				
				// append一个操作
				$actions->append("<a href=\"/admin/spec/$spec_id\"><i class=\"fa fa-eye\"></i></a>");
			});
			$grid->tools(function ($tools) {
				$tools->batch(function ($batch) {
					$batch->disableDelete();
				});
			});
			$grid->disableFilter();//筛选
			$grid->disableCreateButton();//新增
			$grid->disableExport();//导出
			
//			$grid->disableActions();//行操作
			$grid->disableRowSelector();//CheckBox
		});
		$show->sku_postages('快递方式', function ($grid) {
			$grid->postage_id('快递方式')->display(function($postage_id) {
				return Postage::find($postage_id)->name;
			});
			$grid->column('费用')->display(function() {
				return Postage::find($this->postage_id)->cost;
			});
			//行
			$grid->actions(function ($actions) {
				$actions->disableDelete();
				$actions->disableEdit();
				$actions->disableView();
				// 当前行的数据数组
				$postage_id=$actions->row['postage_id'];
				
				// 获取当前行主键值
				$actions->getKey();
				
				// append一个操作
				$actions->append("<a href=\"/admin/postage/$postage_id\"><i class=\"fa fa-eye\"></i></a>");
			});
			$grid->tools(function ($tools) {
				$tools->batch(function ($batch) {
					$batch->disableDelete();
				});
			});
			$grid->disableFilter();//筛选
			$grid->disableCreateButton();//新增
			$grid->disableExport();//导出
			
//			$grid->disableActions();//行操作
			$grid->disableRowSelector();//CheckBox
		});
		
		return $show;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form($item=null)
	{
		$form = new Form(new GoodsSKU);
		
		$form->number('sku_no', 'Sku编号');
		$form->text('sku_name', '子商品名称')->default('默认商品');
		$form->decimal('price', '价格')->default(0.00);
		$form->number('stock', '库存');
//        $form->number('shop_id', 'Shop id');
		$form->number('spu_id', '商品Spu id')->default(request('spu_id'));
		$form->radio('stock_type', '减库存时间')
			->options([0 => '付款减库存', 1 => '下单减库存']);
		$form->switch('postage', '是否包邮');
		$form->number('order', '排序');
		
	    $form->hasMany('sku_spec_values', '规格值', function (Form\NestedForm $form) {
		    $specs = GoodsSpec::all();
		    $options = array();
		    foreach ($specs as $spec) {
			    $options[$spec->id] = "【 $spec->spec_name 】";
		    }
		    $form->select('spec_id', '规格名称')->options($options)
			    ->load('spec_value_id', '/api/admin/spec/getValueBySpec_id');
		    $spec_values=GoodsSpecValue::all();
		    $options_2=array();
		    foreach ($spec_values as $spec_value){
			    $options_2[$spec_value->id] =  $spec_value->value;
		    }
		    $form->select('spec_value_id')->options($options_2);
	    });
	    
		$form->hasMany('sku_postages','快递方式', function (Form\NestedForm $form) {
			$postages = Postage::all();
			$options = array();
			foreach ($postages as $postage) {
				$options[$postage->id] = '【' . $postage->name . '】（价格' . $postage->cost . '）';
			}
			$form->select('postage_id', '邮寄方式')->options($options);
		});
//		$form->ignore(['spec_id']);
		return $form;
	}
}
