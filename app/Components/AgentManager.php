<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2018/12/21
 * Time: 14:26
 */

namespace App\Components;


use App\Models\Agent;
use App\Models\AgentCash;
use App\Models\AgentFinance;
use App\Models\AgentRebate;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AgentManager
{
    public static function getOrders(Agent $agent)
    {
//        $users = $agent->users;
        $order_agent = $agent->order_agent()->with("order")->get();
//        return $users;
//        $orders = $users->mapWithKeys(function ($item) {
//            return $item->orders;
//        });
        $orders = $order_agent->map(function ($item) {
            return $item->order;
        });
        return $orders;
    }


    public static function makeFinance(Agent $agent, $income = 0.0, $expenditure = 0.0, $note = null)
    {
        $agent->balance = (float)$agent->balance + $income - $expenditure;
        $data = [
            "agent_id" => $agent->id,
            'income' => $income ? $income : 0,
            'expenditure' => $expenditure ? $expenditure : 0,
            'balance' => $agent->balance,
            "note" => $note];
        Log::info(json_encode($data));
        $agent_finance = new AgentFinance($data);
        $agent->save();
        $agent_finance->save();
        return $agent_finance;
    }

    public static function getRebateRate(Agent $agent)
    {
        $percent = 0;
        $orders = self::getOrders($agent);
        $s = $orders->sum("payment");//总销售额
        $rebates = AgentRebate::orderBy("step", "asc")->get();
        foreach ($rebates as $rebate) {
            if ($s >= $rebate->step)
                $percent = $rebate->percent;
        }
        return $percent;

    }

    public static function cash(Agent $agent, User $user, $amount)
    {
        if ($agent->balance < $amount)//提现金额不足
        {
            return ["result" => false, "message" => "可提现金额不足"];
        } else {
            $a_f=AgentManager::makeFinance(
                $agent,
                0, $amount,
                "提现扣款");
            $agent->cashes()->create(["user_id" => $user->id, "amount" => (int)($amount * 100)]);
            $agent->save();
            return ["result" => true, "message" => "提现申请已经提交，您可以在一小时内修改或撤销您的申请"];
        }

    }

    public static function doCash(AgentCash $agentcash)
    {
        $pay = new WXPayManager();
        $ret = $pay->transfer($agentcash->amount, $agentcash->id, $agentcash->user->WX->openId, "Calex代理商提现");
//        $ret = $pay->transfer(31, $agentcash->id, $agentcash->user->WX->openId, "申请提现");

        $agentcash->return = $ret;
        $result = $ret['result_code'] == "SUCCESS";
        if ($result) {
            $agentcash->status = 1;
            $agentcash->note = "转账成功";
        } else {
            $agentcash->status = 2;
            $agentcash->note = $ret['return_msg'];
//            $agentcash->agent()->increment("balance", $agentcash->amount / 100);
            $a_f=AgentManager::makeFinance(
                $agentcash->agent,
                $agentcash->amount / 100, 0,
                "提现失败，返还提现金额");
        }

        $agentcash->save();
        return $agentcash;
    }

    public static function doCash_all(){
        $acs=AgentCash::where("status","0")->get();
        foreach ($acs as $ac){
            self::doCash($ac);

        }
        Log::info(Carbon::now()." : 共有".count($acs)."条提现");
    }


}