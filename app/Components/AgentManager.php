<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2018/12/21
 * Time: 14:26
 */

namespace App\Components;


use App\Models\Agent;

class AgentManager
{
    public static function getOrders(Agent $agent){
        $users=$agent->users;
//        return $users;
        $orders=$users->mapWithKeys(function ($item) {
            return $item->orders;
        });
        return $orders;
    }
}