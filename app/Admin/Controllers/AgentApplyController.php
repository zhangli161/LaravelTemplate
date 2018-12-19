<?php

namespace App\Admin\Controllers;

use App\Components\MessageManager;
use App\Models\AgentApply;
use App\Http\Controllers\Controller;
use App\Models\NativePlaceRegion;
use App\Models\NewAdminToken;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;

class AgentApplyController extends Controller
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
		$grid = new Grid(new AgentApply);
		
		$grid->id('Id');
		$grid->user_id('用户名')->display(function ($user_id) {
			$user = User::find($user_id);
//	        $count = count($comments);
//	        $user=json_encode($user);
			return "<a class='label label-warning' href='/admin/users/{$user['id']}'>{$user['name']}</a>";
		});
		$grid->real_name('真实姓名');
		$grid->gender('性别')->using(["0" => "男", "1" => "女"]);
		$grid->telephone('电话号码');
		$grid->address('地址');
//        $grid->region_id('代理区域');
		$grid->region_id('代理区域')->display(function ($region_id) {
			$region = NativePlaceRegion::find($region_id);
			
			return "<label class='label label-success'>{$region['region_name']}</label>";
		});
//        $grid->wx('微信号');
//        $grid->qq('QQ号');
//        $grid->email('邮箱');
//        $grid->business('从事行业');
//        $grid->store('门店信息');
		$grid->status('状态')->using([
			'0' => "<label class='label label-default'>未处理</label>",
			'1' => "<label class='label label-success'>已通过</label>",
			'2' => "<label class='label label-waring'>已驳回</label>"
		])->sortable();
		$grid->created_at('Created at');
		$grid->updated_at('Updated at');
		
		$grid->tools(function ($tools) {
			$tools->batch(function ($batch) {
				$batch->disableDelete();
			});
		});
		$grid->disableCreateButton();
		$grid->disableExport();
		$grid->disableRowSelector();
		$grid->actions(function ($actions) {
			$actions->disableDelete();
//		    $actions->disableEdit();
//		    $actions->disableView();
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
		$show = new Show(AgentApply::findOrFail($id));
		
		$show->id('Id');
		$show->user_id('User id');
		$show->real_name('真实姓名');
		$show->gender('性别')->using(["0" => "男", "1" => "女"]);
		$show->telephone('联系电话');
		$show->address('地址');
		$show->region_id('合作区域');
		$show->wx('微信号');
		$show->qq('QQ号');
		$show->email('邮箱');
		$show->business('从事行业');
		$show->store('门店信息');
		$show->status('Status');
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
		$form = new Form(new AgentApply);
		
		$form->display('user.name', '用户');
		$form->display('real_name', '真实姓名');
		$form->display('gender', '性别')->with(function ($gender) {
			return ["0" => "男", "1" => "女"][$gender];
		});
		$form->display('telephone', '联系电话');
		$form->display('address', '地址');
		$form->display('region.region_name', '合作区域');
		$form->display('wx', '微信号');
		$form->display('qq', 'QQ号');
		$form->display('email', '邮箱');
		$form->display('business', '从事行业');
		$form->display('store', '门店信息');
//		$form->select('status', '状态');
		$form->radio('status', '状态')
			->options(['1' => '通过', '2' => '驳回'])->default('1')->rules("required");
		$form->textarea('note', "备注");
		$form->saved(function (Form $form) {
			if ($form->model()->status == 1) {
				
				$newAdmin_token = NewAdminToken::withTrashed()->where(
					'agent_apply_id', $form->model()->id)->first();
				if (!$newAdmin_token) {
					$token = md5(random_bytes(10));
					$newAdmin_token = NewAdminToken::create(
						['agent_apply_id' => $form->model()->id,
							'token' => $token]);
					
					$user = User::find($form->model()->user_id);
					$url = env("APP_URL") . "/admin";
					$message = MessageManager::sendToUser($user, "代理商申请通过",
						"尊敬的用户:<br/>
您的代理商申请已经通过，请<a herf='$token'>点击这里</a>设置代理商登录信息。
该链接仅一次有效，请设置后牢记您的登录名和密码。
后台登录地址:$url 。"
					);
					$success = new MessageBag([
						'title' => '审核成功',
						'message' => '消息已发送至申请人站内信箱'
//						. json_encode($newAdmin_token),
					]);
				}
				
				return back()->with(compact('success'));
			};
			
		});
		
		return $form;
	}
}