<?php

namespace App\Admin\Controllers;

use App\Models\MessageSource;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class MessageSourceController extends Controller
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
			->header('消息源')
			->description('消息源')
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
			->header('消息源')
			->description('消息源')
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
			->header('消息源')
			->description('消息源')
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
		$grid = new Grid(new MessageSource);
		
		$grid->id('Id');
		$grid->code('英文简称');
		$grid->name('名称');
		$grid->created_at('创建于');
		$grid->updated_at('最后编辑于');

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
		$show = new Show(MessageSource::findOrFail($id));
		
		$show->id('Id');
		$show->code('英文简称');
		$show->name('名称');
		$show->created_at('创建时间');
		$show->updated_at('最后编辑时间');
		
		return $show;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		$form = new Form(new MessageSource);
		
		$form->text('code', '英文简称')->rules('required|alpha_dash')
            ->help('只能由字母、数字、下划线构成');
		$form->text('name', '名称')->rules('required');;
		
		return $form;
	}
}
