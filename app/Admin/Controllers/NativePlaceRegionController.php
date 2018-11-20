<?php

namespace App\Admin\Controllers;

use App\Models\NativePlaceRegion;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Encore\Admin\Tree;

class NativePlaceRegionController extends Controller
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
//		return $content
//			->header('Index')
//			->description('description')
//			->body($this->grid());
		return $content
			->header('地区管理')
			->description('全国地区一览')
			->body(NativePlaceRegion::tree(function ($tree) {
				
				$tree->query(function ($model) {
					return $model->orderby('region_id', 'asc');
				});
				
//				$tree->branch(function ($branch) {
//					$src = config('admin.upload.host') . '/' . $branch['logo'] ;
//					$logo = "<img src='$src' style='max-width:30px;max-height:30px' class='img'/>";
//
//					return "{$branch['id']} - {$branch['title']} $logo";
//				});
				
			}));
//		return Admin::content(function (Content $content) {
//			$content->header('树状模型');
//			$content->body(NativePlaceRegion::tree());
//		});
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
			->header('详情')
//			->description('description')
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
			->header('编辑')
//			->description('description')
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
			->header('新增地区')
//			->description('')
			->body($this->form());
	}
	
	/**
	 * Make a grid builder.
	 *
	 * @return Grid
	 */
	protected function grid()
	{
		$grid = new Grid(new NativePlaceRegion);
	    $grid->model()->where('parentid', '=', 0);
		$grid->paginate(40);
		
		$grid->region_id('地区id')->sortable();
		$grid->parentid('上级地区id')->sortable();
		$grid->region_name('地区名称');
		$grid->have_children('是否有更下级地区');
		
		// filter($callback)方法用来设置表格的简单搜索框
		$grid->filter(function ($filter) {
			$filter->disableIdFilter();
			$filter->like('region_name', '地区名称');
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
		$show = new Show(NativePlaceRegion::findOrFail($id));
		
		$show->region_id('地区id');
		$show->parentid('上级地区id');
		$show->region_name('地区名称');
		$show->have_children('是否有更下级地区');
		
		return $show;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		$form = new Form(new NativePlaceRegion);
		
		$form->number('region_id', '地区id');
		$form->number('parentid', '上级地区id');
		$form->text('region_name', '地区名称');
		$form->switch('have_children', '是否有更下级地区');
		
		return $form;
	}
}
