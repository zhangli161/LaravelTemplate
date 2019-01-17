<?php

namespace App\Admin\Controllers;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BannerController extends Controller
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
			->header('轮播图')
			->description('轮播图')
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
			->header('轮播图')
			->description('')
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
			->header('轮播图')
			->description('轮播图')
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
			->header('新建')
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
		$grid = new Grid(new Banner);
		
		$grid->id('ID')->sortable();
		$grid->order('排序')->editable()->sortable();
		$grid->desc('描述');
		$grid->img_url('图片')->image();
		
		// 设置text、color、和存储值
		$states = [
			'on' => ['value' => 1, 'text' => '生效', 'color' => 'primary'],
			'off' => ['value' => 2, 'text' => '失效', 'color' => 'default'],
		];
		$grid->status()->switch($states);
		$grid->disableExport();
		
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
		$show = new Show(Banner::findOrFail($id));
		
		$show->id('ID');
		$show->desc('描述');
		$show->img_url('图片')->image();
		$show->order('排序');
		$show->status()->using(['0' => '失效', '1' => '生效']);
		$show->attr('附加属性')->unescape()->as(function ($attr) {
			return json_encode($attr);
		});
		$show->updated_at('上次编辑时间');
		
		return $show;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		$form = new Form(new Banner);
		$form->textarea('desc', '简介')->rules("required");
		$form->image('img_url', '图片')->uniqueName()->rules("required");
		$form->number('order', '排序');
		$form->switch('status', '状态');
//	    $form->textarea('attr', '属性 ')->placeholder('key-value形式');


// 自定义标题
		$form->embeds('attr', '附加信息', function ($form) {
		    $form->text("url","跳转链接");
//			$form->number('info_id', '关联信息id')->rules('required');
//		    $form->email('extra2')->rules('required');
//		    $form->mobile('extra3');
//		    $form->datetime('extra4');
			
//			$form->dateRange('time_from', 'time_to', '生效时间')->help('不填写则永久有效');;
		});
		return $form;
	}
}
