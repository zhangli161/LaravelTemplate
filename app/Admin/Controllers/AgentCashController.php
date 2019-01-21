<?php

namespace App\Admin\Controllers;

use App\Models\Agent;
use App\Models\AgentCash;
use App\Http\Controllers\Controller;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class AgentCashController extends Controller
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
            ->header('代理商提现')
            ->description('代理商提现')
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
            ->header('代理商提现')
            ->description('代理商提现')
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
            ->header('代理商提现')
            ->description('代理商提现')
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
            ->header('代理商提现')
            ->description('代理商提现')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AgentCash);
        $grid->model()->orderBy("created_at","desc");

        $grid->id('Id');
        $grid->agent_id('Agent id');
        $grid->user_id('User id');
        $grid->amount('Amount');
        $grid->status('Status');
        $grid->return('Return');
        $grid->note('Note');
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
        $show = new Show(AgentCash::findOrFail($id));

        $show->id('Id');
        $show->agent_id('Agent id');
        $show->user_id('User id');
        $show->amount('Amount');
        $show->status('Status');
        $show->return('Return');
        $show->note('Note');
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
        $form = new Form(new AgentCash);

        $form->number('agent_id', 'Agent id');
        $form->number('user_id', 'User id');
        $form->number('amount', 'Amount');
        $form->switch('status', 'Status');
        $form->textarea('return', 'Return');
        $form->text('note', 'Note');

        return $form;
    }

    public function mine(Content $content)
    {
        return $content
            ->header('Index')
            ->description('')
            ->body($this->grid());
    }

    public function grid_mine()
    {
        $grid = new Grid(new AgentCash);

        $admin = Admin::user();
        $agent = Agent::where("admin_id", $admin->id)->with(["region", "users", "finances"])->first();
        $grid->model()->where("agent_id", $agent->id);

        $grid->id('Id');
        $grid->agent_id('代理商id');
        $grid->user_id('关联小程序用户id');
        $grid->amount('金额');
        $grid->status('状态')->using(['0' => "未执行", "1" => "成功", "2" => "失败"]);
//        $grid->return('Return');
        $grid->note('备注');
        $grid->created_at('申请时间');
//        $grid->updated_at('上次修改时间');

        $grid->disableActions();
        $grid->disableCreateButton();
        $grid->disableExport();
        $grid->disableRowSelector();
        $grid->disableFilter();
        return $grid;
    }
}
