<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2018/12/21
 * Time: 14:26
 */

namespace App\Components;


use App\Models\Agent;
use App\Models\AgentFinance;
use App\Models\AgentRebate;

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


    public static function makeFinance(Agent $agent, $income = 0, $expenditure = 0, $note = null)
    {
        $agent->balance += $income - $expenditure;
        $agent_finance = new AgentFinance([
            "agent_id" => $agent->id,
            'income' => $income ? $income : 0,
            'expenditure' => $expenditure ? $expenditure : 0,
            'balance' => $agent->balance,
            "note" => $note]);
        $agent->save();
        $agent_finance->save();
        return $agent_finance;
    }

    public static function getRebateRate(Agent $agent){
        $percent=0;
        $orders=self::getOrders($agent);
        $s=$orders->sum("payment");//总销售额
        $rebates=AgentRebate::orderBy("step","asc")->get();
        foreach ($rebates as $rebate){
            if ($s>=$rebate->step)
                $percent=$rebate->percent;
        }
        return $percent;

    }
}