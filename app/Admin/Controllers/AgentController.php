<?php

namespace App\Admin\Controllers;

use App\Models\Agent;
use App\Http\Controllers\Controller;
use App\Models\AgentApply;
use App\Models\NativePlaceRegion;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class AgentController extends Controller
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
		if (request('apply_id')) {
			return $content
				->header('Create')
				->description('description')
				->body($this->form(
					AgentApply::find(request('apply_id')
					)));
		}
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
		$grid = new Grid(new Agent);
		
		$grid->id('Id');
		$grid->admin_id('Admin id');
		$grid->real_name('真实姓名');
		$grid->gender('性别');
		$grid->telephone('联系电话');
		$grid->address('地址');
		$grid->region_id('合作区域');
		$grid->wx('微信号');
		$grid->qq('QQ');
		$grid->email('邮箱');
		$grid->business('从事行业');
		$grid->store('门店信息');
		$grid->status('Status');
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
		$show = new Show(Agent::findOrFail($id));
		
		$show->id('Id');
		$show->admin('管理员信息',function ($admin) {
			$admin->setResource('/auth/users');
			$admin->id();
			$admin->name();
		});
		$show->real_name('真实姓名');
		$show->gender('性别');
		$show->telephone('联系电话');
		$show->address('地址');
		$show->region_id('合作区域')->as(function ($region_id) {
			return NativePlaceRegion::find($region_id)->region_name;
		});
		$show->wx('微信号');
		$show->qq('QQ');
		$show->email('邮箱');
		$show->business('从事行业');
		$show->store('门店信息');
		$show->status('Status');
		$show->qr("推广二维码")->lightbox();
		$show->qr("推广二维码")->unescape()->as(function ($qr) {
			if($qr){
				$html="<image src='$qr'></image>";
			}
			else{
				$html="<a href='/admin/'>生成二维码</a>";
			}
			return $html;
		});
		$show->created_at('Created at');
		$show->updated_at('Updated at');
		
		return $show;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form($apply = null)
	{
		$form = new Form(new Agent);
		
		$form->number('admin_id', 'Admin id');
		$form->text('real_name', '真实姓名')
			->default($apply ? $apply->real_name : "")->rules('required');
		$form->switch('gender', '性别')
			->default($apply ? $apply->gender : "")->rules('required');
		$form->text('telephone', '联系电话')
			->default($apply ? $apply->telephone : "")->rules('required');
		$form->text('address', '地址')
			->default($apply ? $apply->address : "")->rules('required');
		$form->number('region_id', '合作区域')
			->default($apply ? $apply->region_id : "")->rules('required');
		$form->text('wx', '微信号')
			->default($apply ? $apply->wx : "");
		$form->text('qq', 'QQ')
			->default($apply ? $apply->qq : "");
		$form->email('email', '邮箱')
			->default($apply ? $apply->email : "");
		$form->text('business', '从事行业')
			->default($apply ? $apply->business : "");
		$form->textarea('store', '门店信息')
			->default($apply ? $apply->store : "");
		$form->switch('status', 'Status');
		
		return $form;
	}
}