<?php

namespace App\Admin\Controllers;

use App\Components\NativePalceReagionManager;
use App\Http\Controllers\Api\RegionController;
use App\Models\User_Address;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class UserAddressController extends Controller
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
			->header('收货地址')
//            ->description('description')
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
//	public function edit($id, Content $content)
//	{
//		return $content
//			->header('Edit')
//			->description('description')
//			->body($this->form()->edit($id));
//	}
	
	/**
	 * Create interface.
	 *
	 * @param Content $content
	 * @return Content
	 */
	public function create(Content $content)
	{
		return $content
			->header('新增地址')
//			->description('description')
			->body($this->form());
	}
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		$grid = new Grid(new User_Address);
		
		$grid->id('id');
//        $grid->user_id('User id');
		$grid->user('用户名')->display(function ($user) {
//	        $count = count($comments);
//	        $user=json_encode($user);
			return "<a class='label label-warning' href='/admin/users/{$user['id']}'>{$user['name']}</a>";
		});
		$grid->region()->display(function ($region) {
			return NativePalceReagionManager::getFullAddress($region['region_id']);
		});
		$grid->address('详细地址');
		$grid->mobile('手机号码');
		
		$grid->filter(function ($filter) {
			
			// 去掉默认的id过滤器
			$filter->disableIdFilter();
			
			// 在这里添加字段过滤器
			$filter->equal('user_id', '用户id');
			$filter->equal('region_id', '地区id');
			
		});
		$grid->actions(function ($actions) {
			$actions->disableDelete();
			$actions->disableEdit();
//			$actions->disableView();
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
		$show = new Show(User_Address::findOrFail($id));
		$show->panel()
			->tools(function ($tools) {
				$tools->disableEdit();
//				$tools->disableList();
				$tools->disableDelete();
			});;
		
		$show->id('id');
		
		$show->user_id('用户id');
		$show->user('用户信息', function ($user) {
			$user->panel()
				->tools(function ($tools) {
					$tools->disableEdit();
					$tools->disableList();
					$tools->disableDelete();
				});;
//	        $show->id('用户id');
			$user->name('姓名');
			$user->password('密码');
			$user->avatar('头像')->image();;
			$user->remember_token('Remember token');
			$user->created_at('注册时间');
			$user->updated_at('最后修改时间');
		});
//		$show->region_id('地区id');
		$show->region_id('地区名称')->as(function ($region_id){
			$content = NativePalceReagionManager::getFullAddress($region_id);
			return $content;
		});;
		
//		$show->region('地区信息', function ($region) {
//			$region->region_name('地区名称')->as(function ($content) use ($region) {
//				$content = NativePalceReagionManager::getFullAddress($region['region_id']);
//				return $content;
//			});
//		});
		$show->address('详细地址');
		$show->mobile('联系电话');
		
		
		return $show;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		$form = new Form(new User_Address);
		
		$form->number('user_id', '用户id')->rules('required');
		
		$provinces = NativePalceReagionManager::getByParentId(0);
		$options = array();
		foreach ($provinces as $province) {
			$options[$province->region_id] = $province->region_name;
		}
		
		$form->select('address1', '一级行政区划')->options($options)->load('address2', '/api/region/getByParentid')->rules('required');;
		$form->select('address1')->options(function ($id) {
			$data = NativePalceReagionManager::getModle()->find($id);
			
			if ($data) {
				return [$data->id => $data->name];
			}
		})->ajax('/api/regions');
		$form->text('address', '详细地址')->rules('required');
		$form->mobile('mobile', '手机号码')->options(['mask' => '999 9999 9999'])->rules('required');;
		
		$form->ignore(['address1', 'address2', 'address3']);
		$form->saving(function (Form $form) {
			$form->username = 0;
			$region_id = max($form->address1, $form->address2, $form->address3);
			$form->display('region_id', '地区id')->value($region_id);
			
		});
		return $form;
	}
}
