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
            $content->body(Category::tree(function ($tree) {
                $tree->branch(function ($branch) {
                    $src = "aaa";
//                    $logo = "<img src='$src' style='max-width:30px;max-height:30px' class='img'/>";
//                    $div = "<div class='margin pull-right' style=''></div>";
                    if ($branch['parentid'] == 0)
                        return "<span class='label label-success'>{$branch['id']} - {$branch['name']}</span>
                        <i class='margin pull-right'></i>
                        ";
                    else
                        return "<span class='label label-primary'>{$branch['id']} - {$branch['name']}</span>";
                });
            }));
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
            ->header('分类')
            ->description('分类')
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
            ->header('分类')
            ->description('分类')
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
        $grid = new Grid(new Category);
        $grid->model()->orderBy("created_at", "desc");

        $grid->id('Id');
        $grid->created_at('创建时间');
        $grid->updated_at('更新时间');
//        $grid->deleted_at('Deleted at');
        $grid->name('名称');
        $grid->order('排序');
//        $grid->parentid('Parentid');

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
        $form->image('icon', "图标");
        $form->image('image', "图片");
        $form->select('parentid', '父级类别')->options('/api/admin/category');
        $form->embeds('attr', '附加信息', function ($form) {
            $form->text("url", "跳转链接");
//			$form->number('info_id', '关联信息id')->rules('required');
//		    $form->email('extra2')->rules('required');
//		    $form->mobile('extra3');
//		    $form->datetime('extra4');

//			$form->dateRange('time_from', 'time_to', '生效时间')->help('不填写则永久有效');;
        });
        return $form;
    }
}
