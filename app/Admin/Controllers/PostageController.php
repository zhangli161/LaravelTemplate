<?php

namespace App\Admin\Controllers;

use App\Models\NativePlaceRegion;
use App\Models\Postage;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class PostageController extends Controller
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
		$grid = new Grid(new Postage);
		
		$grid->id('Id');
		$grid->name('Name');
		$grid->cost('Cost');
		$grid->created_at('创建时间');
		$grid->updated_at('更新时间');
//        $grid->deleted_at('Deleted at');
		
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
		$show = new Show(Postage::findOrFail($id));
		
		$show->id('Id');
		$show->name('Name');
		$show->cost('Cost');
		$show->created_at('创建时间');
		$show->updated_at('更新时间');
//        $show->deleted_at('Deleted at');
		
		return $show;
	}
	
	/**
	 * Make a form builder.
	 *
	 * @return Form
	 */
	protected function form()
	{
		$form = new Form(new Postage);
		
		$form->text('name', '名称');
		$form->decimal('cost', '费用');
		$form->hasMany("postage_regions", "适用地区", function (Form\NestedForm $form) {
			$proviences=NativePlaceRegion::where("parentid",0)->get();
			$options=array_combine($proviences->pluck("region_id")->toArray(),$proviences->pluck("region_name")->toArray());
			$form->select('region_id', "请选择地区")->options($options);
		});
		return $form;
	}
}

