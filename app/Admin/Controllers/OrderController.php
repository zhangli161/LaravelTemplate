<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\User;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class OrderController extends Controller
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

//    /**
//     * Create interface.
//     *
//     * @param Content $content
//     * @return Content
//     */
//    public function create(Content $content)
//    {
//        return $content
//            ->header('Create')
//            ->description('description')
//            ->body($this->form());
//    }
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		$grid = new Grid(new Order);
		
		$grid->id('Id')->sortable();
		$grid->payment('商品实付金额');
		$grid->payment_type('支付方式')->using([1 => '在线支付', 2 => '货到付款']);
		$grid->post_fee('快递费用');
		$grid->status('订单状态')->display(function ($status) {
			
			return [
				1 => "<lable class='label label-default'>未付款</lable>",
				2 => "<lable class='label label-info'>已付款</lable>",
				3 => "<lable class='label label-warning'>未发货</lable>",
				4 => "<lable class='label label-primary'>已发货</lable>",
				5 => "<lable class='label label-success'>交易成功</lable>",
				6 => "<lable class='label label-danger'>交易关闭</lable>",
			][$status];
		})->sortable();
//        $grid->paid_at('Paid at');
//        $grid->consigned_at('Consigned at');
//        $grid->completed_at('Completed at');
//        $grid->closed_at('Closed at');
//		$grid->user_id('用户id');
		$grid->user_id('用户名')->display(function ($user_id) {
			$user = User::find($user_id);
//	        $count = count($comments);
//	        $user=json_encode($user);
			return "<a class='label label-warning' href='/admin/users/{$user['id']}'>{$user['name']}</a>";
		});
//        $grid->receiver_name('Receiver name');
//        $grid->receiver_phone('Receiver phone');
//        $grid->receiver_region_id('Receiver region id');
//        $grid->receiver_address('Receiver address');
//        $grid->buyer_message('Buyer message');
//        $grid->buyer_nick('Buyer nick');
		$grid->postage("快递处理状态")->display(function ($postage) {
			return $postage ?
				"<lable class='label label-success'>是</lable>" :
				"<lable class='label label-danger'>否</lable>";
		});
		$grid->created_at('Created at')->sortable();
		$grid->updated_at('Updated at')->sortable();
		$grid->actions(function ($actions) {
			$actions->disableDelete();
//			$actions->disableEdit();
//			$actions->disableView();
		});
		$grid->filter(function ($filter) {
			// 设置created_at字段的范围查询
			$filter->between('created_at', '下单时间')->datetime();
			
			$filter->equal('status', "订单状态")->select([
				1 => "未付款 ",
				2 => "已付款 ",
//				3 => "未发货 ",
				4 => "已发货 ",
				5 => "交易成功 ",
				6 => "交易关闭	",
			]);
			// 关联关系查询
			$filter->scope('is_posted', "付款未发货订单")->where('status', 2)->doesntHave('postage');
		});

//		$grid->disableFilter();//筛选
		$grid->disableCreateButton();//新增
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
		$show = new Show(Order::findOrFail($id));
		
		$show->id('Id');
		$show->payment('Payment');
		$show->payment_type('Payment type');
		$show->post_fee('Post fee');
		$show->status('Status');
		$show->paid_at('Paid at');
		$show->consigned_at('Consigned at');
		$show->completed_at('Completed at');
		$show->closed_at('Closed at');
		$show->user_id('User id');
		$show->receiver_name('Receiver name');
		$show->receiver_phone('Receiver phone');
		$show->receiver_region_id('Receiver region id');
		$show->receiver_address('Receiver address');
		$show->buyer_message('Buyer message');
		$show->buyer_nick('Buyer nick');
		$show->created_at('Created at');
		$show->updated_at('Updated at');
		$show->postage('快递信息', function ($author) {
			$author->postage_name("快递名称");
			$author->postage_code("快递单号");
			$author->status("状态")->using([
				"0" => "无信息",
				"1" => "运输中",
				"2" => "已收货",
			]);
			$author->updated_at("物流更新时间");
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
		$form = new Form(new Order);

//	    $form->decimal('payment', 'Payment');
//        $form->switch('payment_type', 'Payment type')->default(1);
//        $form->decimal('post_fee', 'Post fee');
		$form->select('status', '订单状态')->default(4)->options([4 => '已发货']);
//        $form->datetime('paid_at', 'Paid at')->default(date('Y-m-d H:i:s'));
        $form->datetime('consigned_at', '发货时间')->default(date('Y-m-d H:i:s'));
//        $form->datetime('completed_at', 'Completed at')->default(date('Y-m-d H:i:s'));
//        $form->datetime('closed_at', 'Closed at')->default(date('Y-m-d H:i:s'));
//        $form->number('user_id', 'User id');
//        $form->text('receiver_name', 'Receiver name');
//        $form->text('receiver_phone', 'Receiver phone');
//        $form->number('receiver_region_id', 'Receiver region id');
//        $form->text('receiver_address', 'Receiver address');
//        $form->text('buyer_message', 'Buyer message');
//        $form->text('buyer_nick', 'Buyer nick');
		
		$form->text("postage.postage_name", '快递名称');
		$form->text("postage.postage_code", '快递单号');
		
		return $form;
	}
}
