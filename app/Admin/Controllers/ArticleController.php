<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ArticleController extends Controller
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
        $grid = new Grid(new Article);

        $grid->id('Id');
        $grid->cate_id('分类');
        $grid->title('标题');
        $grid->desc('描述');
//        $grid->content('内容');
        $grid->author('作者');
        $grid->hits('点击量');
        $grid->on_top('置顶');
        $grid->thumb('封面图片');
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
        $show = new Show(Article::findOrFail($id));

        $show->id('Id');
        $show->cate_id('分类')->as(function ($cate_id) {
        	$category=Category::find($cate_id);
	        return $category->name;
        });
        $show->title('标题');
        $show->desc('描述');
        $show->content('内容')->unescape();
        $show->author('作者');
        $show->hits('点击量');
        $show->on_top('置顶')->using(['0'=>"否","1"=>"是"]);
        $show->thumb('封面图片');
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
        $form = new Form(new Article);
	
	    $cates=Category::all(['id','name'])->toArray();
	    $options= array_column($cates, 'name','id');
        $form->select('cate_id', '分类')->options($options);
        $form->text('title', '标题');
        $form->text('desc', '描述');
        $form->editor('content.content', '内容');
        $form->text('author', '作者');
        $form->number('hits', '点击量');
        $form->switch('on_top', '置顶');
        $form->image('thumb', '封面图片');

        return $form;
    }
}
