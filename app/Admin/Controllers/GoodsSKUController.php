<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\MakeBenifit;
use App\Models\GoodsBenefit;
use App\Models\GoodsSKU;
use App\Http\Controllers\Controller;
use App\Models\GoodsSpec;
use App\Models\GoodsSpecValue;
use App\Models\GoodsSPU;
use App\Models\Postage;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;

class GoodsSKUController extends Controller
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
            ->header('细类商品')
            ->description('SKU')
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
            ->header('细类商品')
            ->description('SKU')
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
            ->header('细类商品')
            ->description('SKU')
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
        $grid = new Grid(new GoodsSKU);
        $grid->model()->orderBy("created_at", "desc");

        $grid->disableCreateButton();//新增

        $spu_id = request('spu_id');
        if ($spu_id)
            $grid->model()->where('spu_id', '=', $spu_id);

        $grid->filter(function ($filter) {

            // 去掉默认的id过滤器
//            $filter->disableIdFilter();

            // 在这里添加字段过滤器
            $filter->like('sku_name', '子商品名称');

            $filter->equal('sku_no', 'SKU编号');
        });
        $grid->id('Id');
        $grid->sku_no('Sku编号')->sortable();
        $grid->sku_name('子商品名称')->editable();
        $grid->price('价格（元）')->editable('text');
        $grid->stock('库存量')->sortable();
//        $grid->shop_id('Shop id');
//        $grid->spu_id('Spu id');
//        $grid->stock_type('减库存时间');
//        $grid->postage('是否包邮');
        $grid->order('排序');
        $grid->created_at('创建时间')->sortable();
        $grid->updated_at('更新时间');
//        $grid->deleted_at('Deleted at');
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
                $batch->add('创建活动', new MakeBenifit());
            });
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
        $show = new Show(GoodsSKU::findOrFail($id));

        $show->id('Id');
        $show->sku_no('Sku编号');
        $show->sku_name('子商品名称');
        $show->price('价格');
        $show->stock('库存');
//        $show->shop_id('Shop id');
        $show->spu_id('商品Spu id');
        $show->stock_type('减库存时间');
        $show->postage('是否包邮');
        $show->order('排序');
        $show->created_at('创建时间');
        $show->updated_at('更新时间');
//        $show->deleted_at('Deleted at');
        $show->sku_spec_values('规格', function ($grid) {
            $grid->spec_id('规格')->display(function ($spec_id) {
                return GoodsSpec::find($spec_id)->spec_name;
            });
            $grid->spec_value_id('规格值')->display(function ($spec_value_id) {
                return GoodsSpecValue::find($spec_value_id)->value;
            });
            //行
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();
                // 当前行的数据数组
                $spec_id = $actions->row['spec_id'];

                // 获取当前行主键值
                $actions->getKey();

                // append一个操作
                $actions->append("<a href=\"/admin/spec/$spec_id\"><i class=\"fa fa-eye\"></i></a>");
            });
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $grid->disableFilter();//筛选
            $grid->disableCreateButton();//新增
            $grid->disableExport();//导出

//			$grid->disableActions();//行操作
            $grid->disableRowSelector();//CheckBox
        });
//		$show->sku_postages('快递方式', function ($grid) {
//			$grid->postage_id('快递方式')->display(function ($postage_id) {
//				return Postage::find($postage_id)->name;
//			});
//			$grid->column('费用')->display(function () {
//				return Postage::find($this->postage_id)->cost;
//			});
//			//行
//			$grid->actions(function ($actions) {
//				$actions->disableDelete();
//				$actions->disableEdit();
//				$actions->disableView();
//				// 当前行的数据数组
//				$postage_id = $actions->row['postage_id'];
//
//				// 获取当前行主键值
//				$actions->getKey();
//
//				// append一个操作
//				$actions->append("<a href=\"/admin/postage/$postage_id\"><i class=\"fa fa-eye\"></i></a>");
//			});
//			$grid->tools(function ($tools) {
//				$tools->batch(function ($batch) {
//					$batch->disableDelete();
//				});
//			});
//			$grid->disableFilter();//筛选
//			$grid->disableCreateButton();//新增
//			$grid->disableExport();//导出
//
////			$grid->disableActions();//行操作
//			$grid->disableRowSelector();//CheckBox
//		});

        $show->albums('相册', function (Grid $grid) {
            $grid->setResource('/admin/albums/delete');
            $grid->id();
            $grid->order('排序');
            $grid->url('图片')->lightbox();

            $grid->disableFilter();//筛选
            $grid->disableCreateButton();//新增
            $grid->disableExport();//导出

//            $grid->disableActions();//行操作
            $grid->actions(function ($actions) {
//                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();
            });
            $grid->disableRowSelector();//CheckBox
        });

        $show->benefits('活动', function ($grid) {
            $grid->resource('/admin/benefit');

            $grid->id('Id');
            $grid->title('活动标题');
            $grid->desc('活动描述');
            $grid->price('活动价');
            $grid->origin_price('原价');
            $grid->status('活动状态')->display(function ($status) {
                $ret = "<label class='label label-danger'>未知的状态</label>";
                switch ($status) {
                    case '-1':
                        $ret = "<label class='label label-default'>已结束</label>";
                        break;
                    case '0':
                        $ret = "<label class='label label-primary'>未开始</label>";
                        break;
                    case '1':
                        $ret = "<label class='label label-success'>进行中</label>";
                        break;
                }
                return $ret;
            });

            $grid->actions(function ($actions) {
//			$actions->disableEdit();
//			$actions->disableView();
                $actions->disableDelete();
            });

            $grid->disableFilter();//筛选
//		$grid->disableCreateButton();//新增
            $grid->disableExport();//导出

//			$grid->disableActions();//行操作
            $grid->disableRowSelector();//CheckBox
            return $grid;
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($item = null)
    {
        $form = new Form(new GoodsSKU);

        $form->tab('基本信息', function ($form) {
            $form->number('sku_no', 'Sku编号')->rules('required');
            $form->text('sku_name', '子商品名称')->default('默认商品')->rules('required');
            $form->decimal('price', '价格')->default(0.00)->rules('required');
            $form->number('stock', '库存')->rules('required');
//        $form->number('shop_id', 'Shop id');
            $spus = GoodsSPU::all();
//            $options = $spus->mapWithKeys(function ($item) {
//                return [$item['id'] => $item['spu_name']];
//            });
            $options=$spus->pluck('spu_name','id');

            $form->select('spu_id', '所属商品')->options($options)->default(request('spu_id'))->rules('required');
            $form->radio('stock_type', '减库存时间')
                ->options([0 => '付款减库存', 1 => '下单减库存'])->rules('required');
            $form->switch('postage', '是否包邮');
            $form->number('order', '排序');
            $form->tags('search_word.search_words', '搜索关键词');
//			Log::info('表单'.json_encode($form->search_word->search_words));
        })->tab('商品规格', function ($form) {
            $form->hasMany('sku_spec_values', '规格值', function (Form\NestedForm $form) {
                $specs = GoodsSpec::all();
                $options = array();
                foreach ($specs as $spec) {
                    $options[$spec->id] = "【 $spec->spec_name 】";
                }
                $form->select('spec_id', '规格类型')->options($options)
                    ->load('spec_value_id', '/api/admin/spec/getValueBySpec_id')
                    ->rules('required');
                $spec_values = GoodsSpecValue::all();
                $options_2 = array();
                foreach ($spec_values as $spec_value) {
                    $options_2[$spec_value->id] = $spec_value->value;
                }
                $form->select('spec_value_id', '规格值')->options($options_2)->rules('required');
            });
//		})->tab('快递方式（包邮则可以不填）', function ($form) {
//			$form->hasMany('sku_postages', '快递方式', function (Form\NestedForm $form) {
//				$postages = Postage::all();
//				$options = array();
//				foreach ($postages as $postage) {
//					$options[$postage->id] = '【' . $postage->name . '】（价格' . $postage->cost . '）';
//				}
//				$form->select('postage_id', '邮寄方式')->options($options);
//			});
        })->tab('商品图片', function (Form $Form) {
            $Form->multipleImage('thumbs', "图片");

//            $Form->text('thumbs');
//            $Form->multipleImage('albums','图片');

//            $Form->hasMany('albums', '商品图片', function (Form\NestedForm $form) use ($Form) {
//                $form->image('url', '图片');
//                $form->number('order', '排序');
//
//                $options = array();
//                if ($Form->model()) {
//                    $skus = GoodsSKU::query()->where('spu_id', $Form->model()->id)->get();
//                    foreach ($skus as $sku) {
//                        $options[$sku->id] = $sku->sku_name;
//                    }
//                }
//            });

        })->tab('相似产品', function ($Form) {
            $Form->hasMany('similar_sku_throughs', '相似产品', function (Form\NestedForm $form) use ($Form) {


                $options = array();
                if ($Form->model()) {
                    $skus = GoodsSKU::query()->get();
                    foreach ($skus as $sku) {
                        $options[$sku->id] = $sku->sku_name;
                    }
                }
                $form->select('similar_sku_id', '关联商品')->options($options);
            });
        })->tab('配套商品', function ($Form) {
            $Form->hasMany('matched_sku_throughs', '配套商品', function (Form\NestedForm $form) use ($Form) {


                $options = array();
                if ($Form->model()) {
                    $skus = GoodsSKU::query()->get();
                    foreach ($skus as $sku) {
                        $options[$sku->id] = $sku->sku_name;
                    }
                }
                $form->select('matched_sku_id', '关联商品')->options($options);
            });
        });
//		$form->ignore(['spec_id']);
        $form->saving(function (Form $form) {

            $exist = GoodsSKU::where("sku_no", $form->sku_no)->first();
//		    dd($exist->id,$form,$form->model()->id);
            if ($exist)
                if ($exist->id != $form->model()->id) {

                    $error = new MessageBag([
                        'title' => '错误',
                        'message' => '编号重复',
                    ]);

                    return back()->with(compact('error'));
                }

//			dd($form);
//			Log::info('表单'.json_encode($form->search_word['search_words']));

            $search_words = $form->search_word['search_words'];
            if ($search_words) {
                $skuname = $form->sku_name;
//			$spuname=null;
                if (!in_array($skuname, $search_words)) {
                    array_unshift($search_words, $skuname);
                }
//			if(!in_array($spuname,$search_words)){
//				array_unshift($search_words,$spuname);
//			}

//			dd($search_words);
                $form->input('search_word.search_words', $search_words);
            }
        });
        $form->saved(function (Form $form) {
            $sku = GoodsSKU::find($form->model()->id);
            $spec_values = $sku->sku_spec_values;
            foreach ($spec_values as $spec_value) {
                $spec_value->spec_id = $spec_value->spec_value->spec_id;
                $spec_value->save();
            }
        });
        return $form;
    }

    public function benifit(Content $content, Request $request)
    {
        $ids = explode(',', $request->get('ids'));

        $form = new Form(new GoodsBenefit());
        $form->setAction('/admin/goods_skus/make_benifit');


        $form->multipleSelect('sku_ids', '子商品id')
            ->options(GoodsSKU::all()->pluck('sku_name', 'id'))
            ->default($ids);
//		$form->display('sku', '子商品id');
        $form->text('title', '活动标题');
        $form->text('desc', '活动描述');
        $form->decimal('price', '活动价比例')
            ->help("以创建活动时商品价格为基准，活动价格的倍数");
        $form->decimal('origin_price', '原价比例')
            ->help("以创建活动时商品价格为基准，活动结束后商品价格的倍数");
        $form->decimal('show_origin_price', '显示原价比例')
            ->help("以创建活动时商品价格为基准，活动时显示的原价的倍数");
        $form->datetimeRange('time_form', 'time_to', '活动时间')->rules('required|after:now');
        $form->switch('reset', '结束时恢复原价')->default(1);

        return $content
            ->header('创建活动')
            ->description('批量创建')
            ->body($form);
    }

    public function benifit_post(Request $request)
    {
//		dd($request->all());
        foreach ($request->get('sku_ids') as $sku_id) {
            $sku = GoodsSKU::find($sku_id);
            if ($sku) {
                GoodsBenefit::create([
                    "sku_id" => $sku->id,
                    "title" => $request->get('title'),
                    "desc" => $request->get('desc'),
                    "price" => $sku->price * $request->get('price'),
                    "origin_price" => $sku->price * $request->get('origin_price'),
                    "show_origin_price" => $sku->price * $request->get('show_origin_price'),
                    "time_form" => $request->get('time_form'),
                    "time_to" => $request->get('time_to'),
//					"reset" => $request->get('reset'),
                ]);
            }
        }
        return redirect()->to('/admin/benefit');
    }

    public function store(Request $request)
    {
        $this->form()->store();
    }
}
