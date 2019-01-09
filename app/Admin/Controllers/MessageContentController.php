<?php

namespace App\Admin\Controllers;

use App\Models\MessageContent;
use App\Http\Controllers\Controller;
use App\Models\MessageSource;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class MessageContentController extends Controller
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
		$grid = new Grid(new MessageContent);
		
		$grid->model()->where('send_type', '=', 1);
		
		$grid->id('Id');
		$grid->title('标题');
//        $grid->content('正文');
//        $grid->send_type('类型');
		$grid->source('消息源')->display(function ($source) {
		return "<a class='label label-success' href='/admin/message_source/{$source['id']}'>{$source['name']}</a>";});
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
		$show = new Show(MessageContent::findOrFail($id));
		
		$show->id('Id');
		$show->title('标题');
		$show->content('正文')->unescape();
		$show->send_type('类型')->using(['0'=>"指定用户","1"=>"群发消息"]);
		$show->source('消息源', function ($source) {
			$source->setResource('/admin/message_source');
			
			$source->id('Id');
			$source->name('名称');
			$source->code('英文简称');
		});
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
		$form = new Form(new MessageContent);
		
		$form->text('title', '标题')->rules('required');
		$form->textarea('content.content', '正文')->help('公告会以富文本形式在小程序端显示')->rules('required');
//        $form->textarea('content', '正文');
		$form->hidden('send_type')->default(1)->rules('required');
//        $form->number('source_id', 'Source id')->rules('required');
		$options = array();
		$sources = MessageSource::all();
		foreach ($sources as $source) {
			$options[$source->id] = $source->name;
		}
		$form->select('source_id', '消息源')->options($options);
		
		return $form;
	}
}
