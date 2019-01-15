<?php

namespace App\Admin\Controllers;

use App\Components\QRManager;
use App\Models\Agent;
use App\Http\Controllers\Controller;
use App\Models\AgentApply;
use App\Models\NativePlaceRegion;
use Carbon\Carbon;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\Facades\Auth;

class AgentsController extends Controller
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
            ->row(function(Row $row) {
                $today=$this_week = Carbon::today();
                $this_week = Carbon::today()->startOfWeek();
//            ->lastOfMonth();
                $this_month = Carbon::today()->startOfMonth();
                $box0=new Box("本日代理商增长",Agent::where("created_at",">=",$today)->count()." 名");
                $box0->style("small-box bg-aqua");
                $row->column(4, $box0);

                $box1=new Box("本周代理商增长",Agent::where("created_at",">=",$this_week)->count()." 名");
                $box1->style("small-box bg-aqua");
                $row->column(4, $box1);

                $box2=new Box("本月代理商增长",Agent::where("created_at",">=",$this_week)->count()." 名");
                $box2->style("small-box bg-aqua");
                $row->column(4, $box2);
//                $row->column(4, 'baz');
            })
            ->row($this->grid());
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
        if (request('apply_id')) {
            return $content
                ->header('Create')
                ->description('description')
                ->body($this->form(
                    AgentApply::find(request('apply_id')
                    )));
        }
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
        $grid = new Grid(new Agent);

        $grid->filter(function($filter){
            // 在这里添加字段过滤器
            $filter->like('real_name', '真实姓名');
        });
        $grid->id('Id');
        $grid->admin_id('Admin id');
        $grid->real_name('真实姓名');
        $grid->gender('性别');
        $grid->telephone('联系电话');
        $grid->address('地址');
        $grid->region_id('合作区域');
        $grid->wx('微信号');
        $grid->qq('QQ');
        $grid->email('邮箱');
        $grid->business('从事行业');
        $grid->store('门店信息');
//        $grid->status('Status');
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
        $show = new Show(Agent::findOrFail($id));
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });

        $show->id('Id');
        $show->admin('管理员信息', function ($admin) {
            $admin->setResource('/admin/auth/users');
            $admin->id();
            $admin->name();
            $admin->panel()
                ->tools(function ($tools) {
                    $tools->disableEdit();
//                    $tools->disableList();
                    $tools->disableDelete();
                });;
        });
        $show->real_name('真实姓名');
        $show->gender('性别');
        $show->telephone('联系电话');
        $show->address('地址');
        $show->region_id('合作区域')->as(function ($region_id) {
            return NativePlaceRegion::find($region_id)->region_name;
        });
        $show->wx('微信号');
        $show->qq('QQ');
        $show->email('邮箱');
        $show->business('从事行业');
        $show->store('门店信息');
//        $show->status('Status');
//        $show->qr("推广二维码")->lightbox();
        $show->xcx_qr("推广二维码")->unescape()->as(function ($qr) use ($id) {
            if ($qr) {
                $html = "
<div>
<div><image src='$qr'></image></div>
<a href='/admin/agent/getQR/$id' title='点击刷新'><button>刷新</button></a>
<label onclick='Download(\"$qr\")' title='点击保存'><button>保存</button></label>


</div>
<script>
function Download(imgdata){
        /*//cavas 保存图片到本地  js 实现
        //------------------------------------------------------------------------
        //1.确定图片的类型  获取到的图片格式 data:image/Png;base64,...... 
        var type ='png';//你想要什么图片格式 就选什么吧
        var d=document.getElementById(\"cavasimg\");
        var imgdata=d.toDataURL(type);
        //2.0 将mime-type改为image/octet-stream,强制让浏览器下载
        var fixtype=function(type){
            type=type.toLocaleLowerCase().replace(/jpg/i,'jpeg');
            var r=type.match(/png|jpeg|bmp|gif/)[0];
            return 'image/'+r;
        };
        imgdata=imgdata.replace(fixtype(type),'image/octet-stream');
        */
        //3.0 将图片保存到本地
        var savaFile=function(data,filename)
        {
            var save_link=document.createElementNS('http://www.w3.org/1999/xhtml', 'a');
            save_link.href=data;
            save_link.download=filename;
            var event=document.createEvent('MouseEvents');
            event.initMouseEvent('click',true,false,window,0,0,0,0,0,false,false,false,false,0,null);
            save_link.dispatchEvent(event);
        };
        var filename='经销商二维码.jpg';  
        //我想用当前秒是可以解决重名的问题了 不行你就换成毫秒
        savaFile(imgdata,filename);
        };
</script>
                ";

            } else {

                $html = "<a href='/admin/agent/getQR/$id'>生成二维码</a>";
            }
//            $html=var_dump($qr);
            return $html;
        });
        $show->balance('待返现金额');
        $this_week = Carbon::today()->startOfWeek();
//            ->lastOfMonth();
        $this_month = Carbon::today()->startOfMonth();

        $show->field("本周粉丝增长")->as(function (){
            $this_week = Carbon::today()->startOfWeek();
            $admin=Auth::guard("admin")->user();
            $agent=Agent::where("admin_id",$admin->id)->first();
            $users_count=$agent->users()->where("bind_agent_time",">=",$this_week)->count();
            return $users_count;
        });

        $show->field("本月粉丝增长")->as(function (){
            $this_month = Carbon::today()->startOfMonth();
            $admin=Auth::guard("admin")->user();
            $agent=Agent::where("admin_id",$admin->id)->first();
            $users_count=$agent->users()->where("bind_agent_time",">=",$this_month)->count();
            return $users_count;
        });

        $show->created_at('Created at');
        $show->updated_at('Updated at');


        $show->finances('流水', function ($grid) {

            $grid->resource('');

            $grid->id('Id');
            $grid->income('收入')->display(function ($text) {
                return $text == 0 ? $text : "<i class='label-primary label'>$text</i>";
            });
            $grid->expenditure('提现')->display(function ($text) {
                return $text == 0 ? $text : "<i class='label-danger label'>$text</i>";
            });
            $grid->balance("余额")->display(function ($text) {
                return "<i class='label-success label'>$text</i>";
            });;
            $grid->note("备注");


            $grid->created_at('时间')->sortable();
//        $grid->updated_at('Updated at');
//
            $grid->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });
            $grid->filter(function ($filter) {

                // 设置created_at字段的范围查询
                $filter->between('created_at', '时间')->datetime();
            });

//        $grid->disableTools();
            $grid->disableActions();
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();
            });

        });

        $show->users('粉丝', function ($grid) {
            $grid->resource('/admin/users');

            $grid->filter(function ($filter) {

                // 设置created_at字段的范围查询
                $filter->between('created_at', '时间')->datetime();
            });

            $grid->id('用户id');//->sortable()表示可排序列
            $grid->name('名称');
            $grid->column('id');
            $grid->avatar("头像")->lightbox();
            // 设置text、color、和存储值
            $states = [
                'on' => ['value' => "1", 'text' => '生效', 'color' => 'success'],
                'off' => ['value' => "2", 'text' => '封禁', 'color' => 'default'],
            ];
            $grid->status('状态')->switch($states);
            $grid->latest_login_time('最后登录时间');
            $grid->bind_agent_time('绑定代理商时间');


//        $grid->disableTools();
//            $grid->disableActions();
            $grid->disableFilter();
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
//                $actions->disableEdit();
//                $actions->disableView();
            });
        });




        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($apply = null)
    {
        $form = new Form(new Agent);

        $form->number('admin_id', '关联管理员ID');
        $form->text('real_name', '真实姓名')
            ->default($apply ? $apply->real_name : "")->rules('required');
        $form->select('gender', '性别')
            ->options(['0'=>"未知","1"=>"男","2"=>"女"])
            ->default($apply ? $apply->gender : "")
            ->rules('required');
        $form->text('telephone', '联系电话')
            ->default($apply ? $apply->telephone : "")->rules('required');
        $form->text('address', '地址')
            ->default($apply ? $apply->address : "")->rules('required');
        $form->number('region_id', '合作区域')
            ->default($apply ? $apply->region_id : "")->rules('required');
        $form->text('wx', '微信号')
            ->default($apply ? $apply->wx : "");
        $form->text('qq', 'QQ')
            ->default($apply ? $apply->qq : "");
        $form->email('email', '邮箱')
            ->default($apply ? $apply->email : "");
        $form->text('business', '从事行业')
            ->default($apply ? $apply->business : "");
        $form->textarea('store', '门店信息')
            ->default($apply ? $apply->store : "");
//        $form->switch('status', 'Status');

        return $form;
    }

    public static function getQR($id)
    {
        $agent = Agent::findOrFail($id);
        $qr = QRManager::getAgentXCXQR($id);
        $agent->update(['xcx_qr' => $qr]);
        $agent->xcx_qr = $qr;
        $agent->save();
        echo"<script>alert('生成成功');history.go(-1);</script>";
//        return redirect()->to("/admin/agent/$id");
    }
}
