<?php

namespace App\Admin\Controllers;

use App\Models\AgentRebate;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class AgentRebateController extends Controller
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
            ->header('代理商返利')
            ->description('代理商返利')
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
            ->header('代理商返利')
            ->description('代理商返利')
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
            ->header('代理商返利')
            ->description('代理商返利')
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
            ->header('代理商返利')
            ->description('代理商返利')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AgentRebate);
        $grid->model()->orderBy("created_at","desc");

        $grid->id('Id');
        $grid->step('门槛值');
        $grid->percent('比例');
        $grid->created_at('创建时间');
        $grid->updated_at('上次修改时间');

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
        $show = new Show(AgentRebate::findOrFail($id));

        $show->id('Id');
        $show->step('门槛值');
        $show->percent('比例');
        $show->created_at('创建时间');
        $show->updated_at('上次修改时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AgentRebate);

        $form->decimal('step', '门槛值');
        $form->rate('percent', '比例');

        return $form;
    }
}
