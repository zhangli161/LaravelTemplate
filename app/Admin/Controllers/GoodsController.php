<?php

namespace App\Admin\Controllers;

use App\Http\Helpers\ApiResponse;
use App\Models\Category;
use App\Models\GoodsSKU;
use App\Models\GoodsSpec;
use App\Models\GoodsSPU;
use App\Http\Controllers\Controller;
use App\Models\Postage;
use Encore\Admin\Admin;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class GoodsController extends Controller
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
            ->header('商品')
            ->description('SPU')
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
            ->header('商品')
            ->description('SPU')
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
        $spu = GoodsSPU::with([
            'specs', 'detail', 'cate', 'sences',
            'skus',
            'skus.search_word', 'skus.albums',
            'skus.matched_skus', 'skus.similar_skus',
            "skus.spec_values"
        ])->findOrFail($id);


        $spu->spec_ids = $spu->specs->pluck("id");
        $spu->sence_ids = $spu->sences->pluck("id");

        foreach ($spu->skus as $sku) {
            $sku->spec_value_ids = $sku->spec_values->pluck("id", 'spec_id');

            $sku->matched_sku_ids = $sku->matched_skus->pluck('id');

            $sku->similar_sku_ids = $sku->similar_skus->pluck('id');
        }

        $skus = GoodsSKU::all();

        return $content
            ->header('创建')
            ->description('')
            ->body(view('admin.goods', [
                    'spu' => $spu,
                    'specs' => GoodsSpec::with('values')->get(),
                    'skus' => $skus,
                ])
            );
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        $spu = GoodsSPU::with([
            'specs', 'detail', 'cate', 'sences',
            'skus',
            'skus.search_word', 'skus.albums',
            'skus.matched_skus', 'skus.similar_skus',
            "skus.spec_values"
        ])->findOrNew(-1);


        $spu->spec_ids = $spu->specs->pluck("id");
        $spu->sence_ids = $spu->sences->pluck("id");

        foreach ($spu->skus as $sku) {
            $sku->spec_value_ids = $sku->spec_values->pluck("id", 'spec_id');

            $sku->matched_sku_ids = $sku->matched_skus->pluck('id');

            $sku->similar_sku_ids = $sku->similar_skus->pluck('id');
        }

        $skus = GoodsSKU::all();

        return $content
            ->header('创建')
            ->description('')
            ->body(view('admin.goods', [
                    'spu' => $spu,
                    'specs' => GoodsSpec::with('values')->get(),
                    'skus' => $skus,
                ])
            );
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new GoodsSPU);

        $grid->model()->orderBy("created_at", "desc");

        $grid->id('Id')->sortable();
//        $grid->spu_no('Spu编号')->sortable();
        $grid->spu_name('商品名称')->editable();
        $grid->desc('描述');
        $grid->status('上架状态')->using(['0' => '下架', '1' => '上架']);
        $grid->thumb('封面图片')->lightbox();;
        $grid->view('浏览量')->sortable();
        $grid->sell('销售量')->sortable();
//        $grid->postage('是否包邮');
        $grid->cate_id('商品分类')->display(function ($cate_id) {
            $cate = Category::find($cate_id);
            $cate_name = $cate ? $cate->name : "未知";
            return "<lable class='label label-primary'>$cate_name</lable>";
        })->sortable();
//        $grid->sence_cate_id('场景分类')->display(function ($cate_id){
//            $cate=Category::find($cate_id);
//            $cate_name=$cate?$cate->name:"未知";
//            return "<lable class='label label-primary'>$cate_name</lable>";
//        })->sortable();
        $grid->created_at('创建时间');
        $grid->updated_at('更新时间');
//        $grid->deleted_at('Deleted at');

        $grid->filter(function ($filter) {

            // 去掉默认的id过滤器
//            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('spu_name', '商品名称');

//            $filter->equal('spu_no', 'SPU编号');
            $options = array();
            $categories = Category::where('parentid', '1')->get();
            foreach ($categories as $category) {
                $options[$category->id] = $category->name;
            }

            $filter->equal('cate_id', '商品分类')->select($options);

            $filter->equal('status', '上架状态')->select(['0' => '下架', '1' => '上架']);
        });
        $grid->actions(function ($actions) {

//			// append一个操作
//			$actions->append('<a href=""><i class="fa fa-eye"></i></a>');

            $spu_id = $actions->getKey();
            // prepend一个操作
            $app_url = env("APP_URL");
            $actions->prepend("<a href=\"$app_url/admin/goods_sku?spu_id=$spu_id\" title='子商品'><i class=\"fa fa-align-left\"></i></a>");
        });

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
        $item = GoodsSPU::findOrFail($id);
        $show = new Show($item);

        $show->id('Id');
//        $show->spu_no('Spu编号');
        $show->spu_name('商品名称');
        $show->desc('描述');
        $show->status('上架状态')
            ->using(['0' => '下架', '1' => '上架'])
            ->label(['0' => 'default', '1' => 'success'][$item->status]);
        $show->thumb('封面图片')->image();;
        $show->view('浏览量');
        $show->sell('销售量');

        $show->detail('商品详情', function ($detail) {
            $detail->content('图文')->unescape();
            $detail->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
                    $tools->disableList();
                    $tools->disableDelete();
                });;
        });

        $show->cate('商品分类')->name("商品分类")->label();
        $show->sences('场景分类')
            ->as(function ($sences) {
                return $sences->pluck("name");
            })
            ->label();
//        $show->sences('场景分类')->name("场景分类")->label();
        $show->created_at('创建时间');
        $show->updated_at('更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
//        $spu = GoodsSPU::with("skus", "detail", 'sences',
//            'skus.sku_spec_values',
//            'skus.albums',
//            'skus.similar_sku_throughs',
//            'skus.matched_sku_throughs')->find(1);
//        return view("admin.goods", ['spu' => $spu]);

        $form = new Form(new GoodsSPU);

        $form->tab('基本信息', function ($form) {

//            $form->number('spu_no', 'Spu编号')->rules('required');
            $form->text('spu_name', '商品名称')->rules('required');
            $form->textarea('desc', '描述')->rules('required');
            $form->switch('status', '上架状态')->rules('required');
            $form->image('thumb', '封面图片')->rules('required');
            $options = array();
            $categories = Category::where('parentid', '1')->get();
            foreach ($categories as $category) {
                $options[$category->id] = $category->name;
            }
            $form->select('cate_id', '商品分类')->options($options)->rules('required');

            $options1 = array();
            $categories = Category::where('parentid', '2')->get();
            foreach ($categories as $category) {
                $options1[$category->id] = $category->name;
            }
//            $form->select('sence_cate_id', '场景分类')
//                ->options($options1)
//                ->rules('required');
            $form->listbox('sences', "场景分类")
                ->options($options1);


        })->tab('图文详情', function ($form) {

            $form->editor('detail.content', '图文详情')->rules('required');

        })->tab('子类商品', function ($form) {
            $form->hasMany('skus', '子类商品', function (Form\NestedForm $form) {
                $form->number('sku_no', 'SKU编号')->rules('required');
                $form->text('sku_name', '子商品名称')->rules('required');
                $form->decimal('price', '价格（元）')->rules('required|min:0');
                $form->number('stock', '库存量')->rules('required');
                $form->radio('stock_type', '减库存时间')
                    ->options([0 => '付款减库存', 1 => '下单减库存'])->rules('required');
                $form->switch('postage', '是否包邮')->rules('required');
                $form->number('order', '排序');
            });

        });

        $form->saving(function (Form $form) {
            $exist = GoodsSPU::where("spu_no", $form->spu_no)->first();

            if ($exist)
                if ($exist->id != $form->model()->id) {

                    $error = new MessageBag([
                        'title' => '错误',
                        'message' => '编号重复',
                    ]);

                    return back()->with(compact('error'));
                }
        });

        return $form;
    }

    public function store(Request $request)
    {
        $spu = GoodsSPU::with('specs')->find($request->get('id'));
        if ($spu)
            $spu->update($request->all());
        else
            $spu = GoodsSPU::create($request->all());
        $spu->save();

        //规格
        $spu->specs()->sync($request->get('spec_ids', []));

        //图文
        $content = $request->get('detail', ['content' => ''])['content'];
        if ($spu->detail()->exists()) {
            $spu->detail()->update(['content' => $content]);
            $spu->detail->save();
        } else {
            $spu->detail()->updateOrCreate(['content' => $content]);
        }

        //场景分类
        $spu->sences()->sync($request->get('sence_ids', []));

        //skus
//                $spu=new GoodsSPU();
        $sku_datas = $request->get('skus');
        if ($request->filled('skus'))
            foreach ($sku_datas as $sku_data) {
                $sku_data['order'] = 0;
                $sku = $spu->skus()->find(array_get($sku_data, 'id'));
                if ($sku) {
                    $sku->update($sku_data);
                } else {
                    $sku = new GoodsSKU($sku_data);
                    $sku = $spu->skus()->save($sku);
                }

                //skus.search_word
                $search_words= array_get($sku_data, 'search_word.search_words');
                if (gettype($search_words)!="array" or count($search_words)==0)
                    $search_words=[array_get($sku_data, 'sku_name')];
                $sku->search_word()->updateOrCreate(["sku_id" => $sku->id],
                    ['search_words' =>$search_words]);

                //skus.albums
//            $album_ids = [];
//            foreach (array_get($sku_data, 'albums') as $album) {
//                $album_now = $sku->albums()
//                    ->updateOrCreate(['url' => array_get($album, 'url')]);
//                array_push($album_ids, $album_now->id);
//            };
//            $sku->albums()->whereNotIn('id', $album_ids)->delete();

//            $sku = new GoodsSKU();

                //matched_skus,similar_skus
                $matched_sku_ids = array_get($sku_data, 'matched_sku_ids');
                $sku->matched_skus()->sync($matched_sku_ids);
//            $sku->matched_skus()->whereNotIn('id',$matched_sku_ids)->delete();

                $similar_sku_ids = array_get($sku_data, 'similar_sku_ids');
                $sku->similar_skus()->sync($similar_sku_ids);
//            $sku->similar_skus()->whereNotIn('id'$similar_sku_ids)->delete();

                $spec_value_ids = array_get($sku_data, "spec_value_ids",[]);
//            dd($spec_value_ids, array_values($spec_value_ids));
//                if ()
                $spec_value_sync = [];
                foreach ($spec_value_ids as $key => $id) {
                    $spec_value_sync[$id] = ['spec_id' => $key];
                }
                $sku->spec_values()->sync($spec_value_sync);
            }

        return ApiResponse::makeResponse(true, $spu);
    }
}
