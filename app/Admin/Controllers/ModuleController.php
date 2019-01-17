<?php

namespace App\Admin\Controllers;

use App\Models\GoodsSKU;
use App\Models\Module;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ModuleController extends Controller
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
            ->header('小程序首页模块')
            ->description('小程序首页模块')
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
            ->header('小程序首页模块')
            ->description('小程序首页模块')
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
            ->header('小程序首页模块')
            ->description('小程序首页模块')
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
        $grid = new Grid(new Module);

        $grid->id('Id');
        $grid->name('模块名称');
        $grid->order('排序');
        $grid->created_at('创建时间');
        $grid->updated_at('上次修改时间');

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
        $show = new Show(Module::findOrFail($id));

        $show->id('Id');
        $show->name('模块名称');
        $show->order('排序');
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
        $form = new Form(new Module);

        $form->text('name', '模块名称')->rules("required");
        $form->number('order', '排序');

        $form->hasMany('module_skus', "显示的商品",function (Form\NestedForm $form) {
            $skus = GoodsSKU::all();
            $options = array_combine($skus->pluck('id')->toArray()
                , $skus->pluck('sku_name')->toArray());
            $form->select("sku_id")
                ->options($options);
        });

        return $form;
    }
}
