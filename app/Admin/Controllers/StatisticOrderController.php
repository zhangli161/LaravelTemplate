<?php

namespace App\Admin\Controllers;

use App\Components\ChartManager;
use App\Components\NativePalceReagionManager;
use App\Models\NativePlaceRegion;
use App\Models\StatisticOrder;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Illuminate\Http\Request;

class StatisticOrderController extends Controller
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
	protected function grid($type = "0")
	{
		$grid = new Grid(new StatisticOrder);
		$grid->model()->where('type', $type);

//		$grid->id('Id');
		$grid->date('统计日期')->sortable();
		$grid->region_id('地区')->display(function ($region_id) {
			if ($region_id == 0) {
				return "<label class='label label-warning'>全国</label>";
			} else {
				$region = NativePlaceRegion::find($region_id);
				if ($region) {
					return "<label class='label label-primary'>{$region->region_name}</label>";
				} else
					return "<label class='label label-danger'>未知</label>";
			}
		})->sortable();
		$grid->orders_count('订单数量');
		$grid->orders_total_payment('订单总金额');
//        $grid->created_at('Created at');
//        $grid->updated_at('Updated at');
		$grid->filter(function ($filter) {
			
			// 去掉默认的id过滤器
			$filter->disableIdFilter();
			
			// 在这里添加字段过滤器
			$filter->equal('type', "统计间隔")->select([
				0 => "日",
				2 => "月",
				3 => "年",
			]);
			$filter->between('date', "统计时间")->datetime();
			$proviences = NativePalceReagionManager::getProviences();
			$options = array_combine($proviences->pluck("region_id")->toArray(), $proviences->pluck("region_name")->toArray());
			$options[0] = "全国";
			ksort($options);
			$filter->equal('region_id', "地区")->select($options);
		});
		
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
		$show = new Show(StatisticOrder::findOrFail($id));
		
		$show->id('Id');
		$show->date('Date');
		$show->region_id('Region id');
		$show->orders_count('Orders count');
		$show->orders_total_payment('Orders total payment');
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
		$form = new Form(new StatisticOrder);
		
		$form->date('date', 'Date')->default(date('Y-m-d'));
		$form->number('region_id', 'Region id');
		$form->number('orders_count', 'Orders count');
		$form->decimal('orders_total_payment', 'Orders total payment')->default(0.00);
		
		return $form;
	}
	
	public function chart1(Content $content, Request $request)
	{
//		return $request->all();
		$query = StatisticOrder::query();
		if ($request->filled("type")) {
			$query->where("type", $request->get("type"));
		} else {
			$query->where("type", 0);
		}
		if ($request->filled("date_from") && $request->filled("date_to")) {
			$query->where("date", ">=", $request->get("date_from"));
			$query->where("date", '<=', $request->get("date_to"));
		}
//		else {
//			$query->where("date", ">=", date_sub(date_create(), date_interval_create_from_date_string("1 month")));
//		}
		if ($request->filled("region_id")) {
			$query->where("region_id", $request->get("region_id"));
		} else {
			$query->where("region_id", 0);
		}
		$model = $query->get();
		if ($request->get("type") == 0) {
			$lables = [];
			$datas = [];
			$from = $request->filled("date_from") ? min($model->min('date'), $request->get("date_from")) : $model->min('date');
			$to = $request->filled("date_to") ? max($model->max('date'), $request->get("date_to")) : $model->max('date');
//			dd( [$from, $to,date('Y-m-d',strtotime($from . " + 1 day"))]);
			for ($date = $from; strtotime($date) <= strtotime($to); $date = date("Y-m-d", strtotime($date . " + 1 day"))) {
				array_push($lables, $date);
				$data = $model->where('date', $date)->first();
				array_push($datas, $data ? $data->orders_count : 0);
			}
		}
		return $content
			->header('订单数量折线图')
//			->description('订单折线图')
			->row(ChartManager::line($lables, '订单数量', $datas))
			->row($this->chartform("/admin/chart/order/count"));
	}
	public function chart2(Content $content, Request $request)
	{
//		return $request->all();
		$query = StatisticOrder::query();
		if ($request->filled("type")) {
			$query->where("type", $request->get("type"));
		} else {
			$query->where("type", 0);
		}
		if ($request->filled("date_from") && $request->filled("date_to")) {
			$query->where("date", ">=", $request->get("date_from"));
			$query->where("date", '<=', $request->get("date_to"));
		}
//		else {
//			$query->where("date", ">=", date_sub(date_create(), date_interval_create_from_date_string("1 month")));
//		}
		if ($request->filled("region_id")) {
			$query->where("region_id", $request->get("region_id"));
		} else {
			$query->where("region_id", 0);
		}
		$model = $query->get();
		if ($request->get("type") == 0) {
			$lables = [];
			$datas = [];
			$from = $request->filled("date_from") ? min($model->min('date'), $request->get("date_from")) : $model->min('date');
			$to = $request->filled("date_to") ? max($model->max('date'), $request->get("date_to")) : $model->max('date');
//			dd( [$from, $to,date('Y-m-d',strtotime($from . " + 1 day"))]);
			for ($date = $from; strtotime($date) <= strtotime($to); $date = date("Y-m-d", strtotime($date . " + 1 day"))) {
				array_push($lables, $date);
				$data = $model->where('date', $date)->first();
				array_push($datas, $data ? $data->orders_total_payment : 0);
			}
		}
		return $content
			->header('订单金额折线图')
//			->description('折线图')
			->row(ChartManager::line($lables, '订单金额', $datas))
			->row($this->chartform("/admin/chart/order/payment"));
	}
	protected function chartform($action)
	{
		$form = new Form(new StatisticOrder);
		$form->tools(function (Form\Tools $tools) {
			
			// 去掉`列表`按钮
			$tools->disableList();
		});
		$form->footer(function ($footer) {
			
			// 去掉`重置`按钮
			$footer->disableReset();
			
			// 去掉`查看`checkbox
			$footer->disableViewCheck();
			
			// 去掉`继续编辑`checkbox
			$footer->disableEditingCheck();
			
			// 去掉`继续创建`checkbox
			$footer->disableCreatingCheck();
			
		});
		$form->setAction($action);
		$form->select('type', '类型')
			->options([0 => "每日统计", 2 => "每月统计", 4 => "每年统计"])
			->default(\request('type') | 0);
		
		$proviences = NativePalceReagionManager::getProviences();
		$options = array_combine($proviences->pluck("region_id")->toArray(), $proviences->pluck("region_name")->toArray());
		$options[0] = "全国";
		ksort($options);
		
		$form->select('region_id', '省份')
			->options($options)
			->default(\request('region_id') | 0);
		
		$form->date('date_from', "开始时间")->default(\request('date_from'));
		$form->date('date_to', "结束时间")->default(\request('date_to'));
		
		return $form;
	}
}
