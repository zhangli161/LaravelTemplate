<?php

namespace App\Admin\Controllers;

use App\Models\CharityActivity;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CharityActivityController extends Controller
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
            ->header('慈善活动')
            ->description('慈善活动')
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
            ->header('详情')
            ->description('详情')
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
            ->description('编辑')
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
        $grid = new Grid(new CharityActivity);

        $grid->id('Id');
        $grid->title('活动标题');
//        $grid->content('活动内容');
        $grid->goal('目标金额');
        $grid->now('当前金额');
//        $grid->reciver('善款接收机构');
        $grid->date_to('结束时间');
        $grid->status('活动状态')->using(['1'=>"进行中","2"=>"已完成",3=>"未完成"]);
        $grid->created_at('创建时间');
//        $grid->updated_at('Updated at');

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
        $show = new Show(CharityActivity::findOrFail($id));

        $show->id('Id');
        $show->title('活动标题');
        $show->content('活动内容');
        $show->goal('目标金额');
        $show->now('当前金额');
        $show->reciver('善款接收机构');
        $show->date_to('结束时间');
        $show->status('活动状态')->using(['1'=>"进行中","2"=>"已完成",3=>"未完成"]);
        $show->created_at('创建时间');
//        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CharityActivity);

        $form->text('title', '活动标题');
        $form->editor('content', '活动内容');
        $form->editor('reciver', '善款接收机构');
        $form->decimal('goal', '目标金额');
        $form->decimal('now', '当前金额');
        $form->datetime('date_to', '结束时间')->default(date('Y-m-d H:i:s'));
        $form->select('status', '活动状态')->default(1)
            ->options(['1'=>"进行中","2"=>"已完成",3=>"未完成"]);

        return $form;
    }
}
