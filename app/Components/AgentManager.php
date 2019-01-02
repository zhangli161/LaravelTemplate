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

class AgentManager
{
    public static function getOrders(Agent $agent)
    {
        $users = $agent->users;
//        return $users;
        $orders = $users->mapWithKeys(function ($item) {
            return $item->orders;
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
}