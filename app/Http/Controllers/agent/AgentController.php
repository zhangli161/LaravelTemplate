<?php

namespace App\Http\Controllers\Agent;


use App\Components\AgentManager;
use App\Components\QRManager;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Agent;
use App\Models\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AgentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.agent:agent');
    }

    //
    public function index()
    {
        $user = Auth::guard("agent")->user();
        $agent = Agent::query()->where("id", $user->id)->with(["region", "users", "finances"])->first();

        $date_form = Carbon::today()->addDays(-6)->toDateString();
        $dates = getDatesBetween($date_form, Carbon::today());

        $fans_increase = array();
        $fans_orders = array();
        $fans = $agent->users;
        $fans_ids = $fans->pluck("id");
        foreach ($dates as $date) {
            $fans_increase[$date] = $agent->users()->whereDate("bind_agent_time", $date)->count();
            $fans_orders[$date] = Order::query()->whereIn("user_id", $fans_ids)
                ->whereDate("created_at", $date)->count();

        }

        return view("agent.index", [
            "agent" => $agent,
            'fans_ids' => $fans_ids,
            "fans_increase" => $fans_increase,
            "fans_orders" => $fans_orders
        ]);
    }

    public function qr()
    {
        $agent = Auth::guard("agent")->user();

        return view("agent.qr", ['agent' => $agent]);
    }

    public function refresh_qr()
    {
        $agent = Auth::guard("agent")->user();

        $qr = QRManager::getAgentXCXQR($agent->id,"pages/start/start");
        $agent->xcx_qr = $qr;
        $agent->save();
        return redirect()->back();
    }

    public function record()
    {
        $agent = Auth::guard("agent")->user();

        $datas = $agent->cashes;

        return view("agent.record", ['datas' => $datas]);
    }

    public function cash()
    {
        $agent = Auth::guard("agent")->user();

        return view("agent.cash", ['agent' => $agent]);
    }

    public function getUser(Request $request)
    {
        $user = User::with("WX")->where("id", $request->get("user_id"))
            ->where("mobile", $request->get("mobile"))->first();
//        return $request->all();
        return ApiResponse::makeResponse(!empty($user), $user);
    }

    public function cash_post(Request $request)
    {
        if (!$request->filled(['payment', "user_id"])) {
            return $this->cash(new Content())
                ->withError("参数丢失", "请将表单完整填写");
        }
        $user = User::findOrFail($request->get("user_id"));
        $agent = Auth::guard("agent")->user();

        $result = AgentManager::cash($agent, $user, $request->get("payment"));

        return ApiResponse::makeResponse($result["result"], $request['message']);
    }

    public function info()
    {
        $agent = Auth::guard("agent")->user();
//            $agent->with("users", "region", "order_agent");
        $orders = AgentManager::getOrders($agent);
        $orders_finish = $orders->where("status", 5);

        $user_ids = $agent->users->pluck("id");
        $rows = [
            ["name" => "代理商id", "value" => $agent->id],
            ["name" => "真实姓名", "value" => $agent->real_name],
            ["name" => "代理地区", "value" => $agent->region->full_address()],
            ["name" => "粉丝人数", "value" => $agent->users->count()],
            ["name" => "累计粉丝订单数量", "value" => $agent->order_agent()->count()],
            ["name" => "累计销售额", "value" => $orders->sum("payment")],
//            ["name" => "返利", "value" => $agent->order_agent()->sum("payment")],
            ["name" => "返利余额", "value" => $agent->balance],
            ["name" => "已提现返利", "value" => $agent->cashed],
            ["name" => "本日新增粉丝数量", "value" => $agent->users()->whereDate('bind_agent_time', Carbon::today())->count()],
            ["name" => "本周新增粉丝数量", "value" => $agent->users()->whereDate('bind_agent_time', '>=', date('Y-m-d', strtotime('last Monday')))->count()],
            ["name" => "本月新增粉丝数量", "value" => $agent->users()->whereDate('bind_agent_time', date('Y-m-d', strtotime('this month')))->count()],
        ];

        return view("agent.info", ["rows" => $rows]);
    }

    public function change_password()
    {
        return view("agent.change_password");
    }

    public function change_password_post(Request $request)
    {
//        return 1;
        $agent = Auth::guard("agent")->user();
//        dd($agent);
        $agent->update(["password" => bcrypt($request->get("password"))]);
        return redirect()->to("agent/logout");
    }

    public function finance()
    {
        $agent = Auth::guard("agent")->user();
        $agent->finances;

        return view("agent.finance", ['agent' => $agent]);
    }
}
