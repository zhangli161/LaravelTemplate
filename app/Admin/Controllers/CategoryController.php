<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CategoryController extends Controller
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
	    return Admin::content(function (Content $content) {
		    $content->header('分类');
		    $content->body(Category::tree());
	    });
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
        $grid = new Grid(new Category);

        $grid->id('Id');
        $grid->created_at('创建时间');
        $grid->updated_at('更新时间');
//        $grid->deleted_at('Deleted at');
        $grid->name('名称');
        $grid->order('排序');
        $grid->parentid('Parentid');

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
        $show = new Show(Category::findOrFail($id));

        $show->id('Id');
        $show->created_at('创建时间');
        $show->updated_at('更新时间');
//        $show->deleted_at('Deleted at');
        $show->name('名称');
        $show->order('排序');
//        $show->parentid('Parentid');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Category);

        $form->text('name', '类别名称');
//        $form->number('order', '排序');
	    $form->image('icon',"图标");
	    $form->image('image',"图片");
        $form->select('parentid', '父级类别')->options('/api/admin/category');

        return $form;
    }
}
