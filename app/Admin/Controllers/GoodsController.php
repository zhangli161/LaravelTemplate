<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\GoodsSKU;
use App\Models\GoodsSPU;
use App\Http\Controllers\Controller;
use App\Models\Postage;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class GoodsController extends Controller
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
		$grid = new Grid(new GoodsSPU);
		
		$grid->id('Id');
		$grid->spu_no('Spu编号');
		$grid->spu_name('商品名称');
		$grid->desc('描述');
		$grid->status('上架状态');
		$grid->thumb('封面图片')->lightbox();;
		$grid->view('浏览量');
		$grid->sell('销售量');
//        $grid->postage('是否包邮');
		$grid->cate_id('分类');
		$grid->created_at('创建时间');
		$grid->updated_at('更新时间');
//        $grid->deleted_at('Deleted at');
		
		$grid->actions(function ($actions) {

//			// append一个操作
//			$actions->append('<a href=""><i class="fa fa-eye"></i></a>');
			
			$spu_id = $actions->getKey();
			// prepend一个操作
			$actions->prepend("<a href=\"http://localhost/admin/goods_sku?spu_id=$spu_id\" title='子商品'><i class=\"fa fa-align-left\"></i></a>");
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
		$item = GoodsSPU::findOrFail($id);
		$show = new Show($item);
		
		$show->id('Id');
		$show->spu_no('Spu编号');
		$show->spu_name('商品名称');
		$show->desc('描述');
		$show->status('上架状态')
			->using(['0' => '下架', '1' => '上架'])
			->label(['0' => 'default', '1' => 'success'][$item->status]);
		$show->thumb('封面图片')->image();;
		$show->view('浏览量');
		$show->sell('销售量');
		$show->albums('相册', function ($grid) {
			
			$grid->id();
			$grid->order('排序');
			$grid->url('图片')->lightbox();
			
			$grid->disableFilter();//筛选
//		$grid->disableCreateButton();//新增
			$grid->disableExport();//导出
			
			$grid->disableActions();//行操作
			$grid->disableRowSelector();//CheckBox
		});
		$show->detail('商品详情', function ($detail) {
			$detail->content('图文')->unescape();
			
		});
		
		$show->cate_id('分类');
		$show->created_at('创建时间');
		$show->updated_at('更新时间');
		
		return $show;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		$form = new Form(new GoodsSPU);
		
		$form->tab('基本信息', function ($form) {
			
			$form->number('spu_no', 'Spu编号')->rules('required');
			$form->text('spu_name', '商品名称')->rules('required');
			$form->textarea('desc', '描述')->rules('required');
			$form->switch('status', '上架状态')->rules('required');
			$form->file('thumb', '封面图片')->rules('required');
			$options=array();
			$categories=Category::where('parentid','0')->get();
			foreach ($categories as $category){
				$options[$category->id]=$category->name;
			}
			$form->select('cate_id', '分类')->options($options);
			
		})->tab('图文详情', function ($form) {
			
			$form->editor('detail.content', '图文详情')->rules('required');
			
		})->tab('子类商品', function ($form) {
			$form->hasMany('skus', '子类商品', function (Form\NestedForm $form) {
				$form->number('sku_no', 'SKU编号')->rules('required');
				$form->text('sku_name', '子商品名称')->rules('required');
				$form->decimal('price', '价格（元）')->rules('required|min:0');
				$form->number('stock', '库存量')->rules('required');
				$form->radio('stock_type', '减库存时间')
					->options([0 => '付款减库存', 1 => '下单减库存'])->rules('required');
				$form->switch('postage', '是否包邮')->rules('required');
				$form->number('order', '排序');
			});
			
		})->tab('商品图片', function ($Form) {
			
			$Form->hasMany('albums', '商品图片', function (Form\NestedForm $form) use ($Form) {
				$form->image('url', '图片')->rules('required');
				$form->number('order', '排序');
				
				$options = array();
				if ($Form->model()) {
					$skus = GoodsSKU::query()->where('spu_id', $Form->model()->id)->get();
					foreach ($skus as $sku) {
						$options[$sku->id] = $sku->sku_name;
					}
				}
				$form->select('sku_id', '关联子商品')
					->options($options)
					->help('非必填。用户选择该子商品时此图片会优先显示。
					只能选择已保存的子商品。
					可以勾选继续编辑后提交再进行关联。');
			});
		});
		
		
		return $form;
	}
}
