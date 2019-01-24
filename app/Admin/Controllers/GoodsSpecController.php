<?php

namespace App\Admin\Controllers;

use App\Models\GoodsSpec;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodsSpecController extends Controller
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
            ->header('商品规格')
            ->description('商品规格')
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
            ->header('商品规格')
            ->description('商品规格')
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
            ->header('商品规格')
            ->description('商品规格')
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
        $grid = new Grid(new GoodsSpec);
        $grid->model()->orderBy("created_at","desc");
        $grid->filter(function ($filter) {

            // 去掉默认的id过滤器
//            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('spec_name', '规格名称');

            $filter->equal('spec_no', '规格编号');
        });
        $grid->id('Id');
        $grid->spec_no('规格编号');
        $grid->spec_name('规格名称');
        $grid->created_at('创建时间');
        $grid->updated_at('更新时间');
//        $grid->deleted_at('Deleted at');
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
        $show = new Show(GoodsSpec::findOrFail($id));

        $show->id('Id');
        $show->spec_no('规格编号');
        $show->spec_name('规格名称');
        $show->created_at('创建时间');
        $show->updated_at('更新时间');
//        $show->deleted_at('Deleted at');
	    $show->values('规格值', function ($grid) {
		    $grid->id();
		    $grid->value('规格值');
		    $grid->actions(function ($actions) {
			    $actions->disableDelete();
			    $actions->disableEdit();
			    $actions->disableView();
			   });
		    $grid->tools(function ($tools) {
			    $tools->batch(function ($batch) {
				    $batch->disableDelete();
			    });
		    });
		    $grid->disableFilter();//筛选
		    $grid->disableCreateButton();//新增
		    $grid->disableExport();//导出
		    
		    $grid->disableActions();//行操作
		    $grid->disableRowSelector();//CheckBox
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
        $form = new Form(new GoodsSpec);

        $form->number('spec_no', '规格编号');
        $form->text('spec_name', '规格名称');
	    $form->hasMany('values','规格值',function (Form\NestedForm $form) {
		    $form->text('value', '规格值');
	    });

        return $form;
    }
}
