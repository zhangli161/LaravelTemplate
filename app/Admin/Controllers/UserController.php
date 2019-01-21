<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\ExcelExpoter;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;

class UserController extends Controller
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
			->header('用户列表')
			->description('用户列表')
            ->row(function (Row $row) {
                $today = $this_week = Carbon::today();
                $this_week = Carbon::today()->startOfWeek();
//            ->lastOfMonth();
                $this_month = Carbon::today()->startOfMonth();
                $box0 = new Box("本日用户增长", User::where("created_at", ">=", $today)->count() . " 名");
                $box0->style("small-box bg-aqua");
                $row->column(4, $box0);

                $box1 = new Box("本周用户增长", User::where("created_at", ">=", $this_week)->count() . " 名");
                $box1->style("small-box bg-aqua");
                $row->column(4, $box1);

                $box2 = new Box("本月用户增长", User::where("created_at", ">=", $this_week)->count() . " 名");
                $box2->style("small-box bg-aqua");
                $row->column(4, $box2);
//                $row->column(4, 'baz');
            })
			->row($this->grid());
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
			->header('用户')
			->description('用户')
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
			->header('用户')
			->description('用户')
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
		$grid = new Grid(new User);
        $grid->model()->orderBy("created_at","desc");

        //直接->字段名（表头）可以在表单中创建一列，某些冲突的名字可以用$grid->column('字段名');
		$grid->id('用户id')->sortable();//->sortable()表示可排序列
		$grid->name('名称');
		$grid->column('id');
		
		// 添加不存在的字段
		//display()方法接收的匿名函数绑定了当前行的数据对象，可以在里面调用当前行的其它字段数据
//
//		$grid->column('id+姓名')->display(function () {
//			return $this->id . ' ' . $this->name;
//		});
		//另外还可以显示任意你需要的内容
//		$grid->password("密码")->display(function ($password) {
//			$isset = isset($password) ? "已设置" : "未设置";
//			return "<span class=''>$isset</span>";
//		})->label('primary');
		////设置颜色，默认`success`,可选`danger`、`warning`、`info`、`primary`、`default`、`success`
		
		/*// 第六列显示released字段，通过display($callback)方法来格式化显示输出
		$grid->released('上映?')->display(function ($released) {
			return $released ? '是' : '否';
		});*/
		$grid->avatar("头像")->lightbox();
		// 设置text、color、和存储值
		$states = [
			'on' => ['value' => "1", 'text' => '生效', 'color' => 'success'],
			'off' => ['value' => "2", 'text' => '封禁', 'color' => 'default'],
		];
		$grid->status('状态')->switch($states);
		$grid->latest_login_time('最后登录时间');
		$grid->created_at('注册时间');
		$grid->updated_at('上次修改时间');
		
		// filter($callback)方法用来设置表格的简单搜索框
		$grid->filter(function ($filter) {
                // 在这里添加字段过滤器
                $filter->like('name', '昵称');

			// 设置created_at字段的范围查询
			$filter->between('created_at', '注册时间')->datetime();
		});
		$grid->actions(function ($actions) {
			$actions->disableDelete();
//			$actions->disableEdit();
//			$actions->disableView();
			$user_id=array_get($actions->row,'id');
//			$user_id=$actions->row
			$actions->append('<a href="/admin/user_address?&user_id='.$user_id.'"><i class="fa fa-map-o"></i></a>');
		});
		
		//禁用创建按钮
		//$grid->disableCreateButton();
//	    禁用分页条
//	    $grid->disablePagination();
//	    禁用查询过滤器
//	    $grid->disableFilter();
//	    禁用导出数据按钮
//	    $grid->disableExport();
//	    禁用行选择checkbox
//	    $grid->disableRowSelector();
//	    禁用行操作列
//	    $grid->disableActions();
//	    设置分页选择器选项
//	    $grid->perPages([10, 20, 30, 40, 50]);
		
		//导出
//		$grid->exporter(new ExcelExpoter());
		
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
		$show = new Show(User::findOrFail($id));
		
		$show->id('用户id');
		$show->name('姓名');
//		$show->password('密码');
		$show->avatar('头像')->image();
//		$show->remember_token('Remember token');
		$show->created_at('注册时间');
		$show->updated_at('最后修改时间');
		
		return $show;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		$form = new Form(new User);
		
		$form->display("id","id");
		$form->text('name', '名称')
			->placeholder('请输入。。。')
			->help('help...');
        $states = [
            'on' => ['value' => "1", 'text' => '生效', 'color' => 'success'],
            'off' => ['value' => "2", 'text' => '封禁', 'color' => 'default'],
        ];
		$form->switch("status","状态")->states($states);
//		$form->password('password', 'Password');
//		$form->text('remember_token', 'Remember token');
		//使用随机生成文件名 (md5(uniqid()).extension)
		$form->image('avatar','头像')->uniqueName();
//		$form->tags('keywords');
//		$form->icon('icon');

		
		//如果表单元素太多,会导致form页面太长, 这种情况下可以使用tab来分隔form:
		/*
		$form->tab('Basic info', function ($form) {
			
			$form->text('username');
			$form->email('email');
			
		})->tab('Profile', function ($form) {
			
			$form->image('avatar');
			$form->text('address');
			$form->mobile('phone');
			
		})->tab('Jobs', function ($form) {
			
			$form->hasMany('jobs', function () {
				$form->text('company');
				$form->date('start_date');
				$form->date('end_date');
			});
			
		});*/
		return $form;
	}
}
