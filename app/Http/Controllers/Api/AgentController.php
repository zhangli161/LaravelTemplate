<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/12/10
 * Time: 13:36
 */

namespace App\Http\Controllers\Api;


use App\Components\AdminManager;
use App\Http\Helpers\ApiResponse;
use App\Models\Agent;
use App\Models\AgentApply;
use App\Models\NewAdminToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController
{
    public static function apply(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        if ($request->filled("id")) {
            $agent_apply = AgentApply::find($request->filled("id"));
        }
        if (!isset($agent_apply))
            $agent_apply = AgentApply::create($data);
        $agent_apply->update($data);
        $agent_apply->save();
        AgentApply::query()
            ->where("user_id",Auth::user()->id)
            ->where("id","<>",$agent_apply->id)
            ->delete();
        return ApiResponse::makeResponse(true, $agent_apply, 200);
    }

    public static function getById(Request $request){
        $agent_apply = AgentApply::findOrFail($request->filled("id"));
        return ApiResponse::makeResponse(true, $agent_apply, 200);
    }

    public static function create_admin(Request $request)
    {
        //校验token
        $token = NewAdminToken::query()->where("agent_apply_id", $request->get('agent_apply_id'))
            ->where("token", $request->get("token"))->first();
        if ($token) {
            $admin = AdminManager::new_admin($request->get("username"), $request->get("password"));
            if ($admin) {
                $token->delete();
                $apply = AgentApply::find($request->get('agent_apply_id'));
                $agent = new Agent([
                    "admin_id" => $admin->id,
                    "real_name" => $apply->real_name,
                    "gender" => $apply->gender,
                    "telephone" => $apply->telephone,
                    "address" => $apply->address,
                    "region_id" => $apply->region_id,
                    "wx" => $apply->wx,
                    "qq" => $apply->qq,
                    "email" => $apply->email,
                    "business" => $apply->business,
                    "store" => $apply->store,
                    "status" => 1
                ]);
                $agent->save();
                $admin = AdminManager::setRoles($admin, 2);//设置运营商权限
                return ApiResponse::makeResponse(true, [$admin, $agent], ApiResponse::SUCCESS_CODE);
            } else {
                return ApiResponse::makeResponse(false, "创建管理员失败，登录名重复", ApiResponse::USERNAME_DUP);
            }
        } else {
            return ApiResponse::makeResponse(false, "令牌失效或不存在", ApiResponse::TOKEN_ERROR);
        }
    }

    public static function mine(){
        $user_id = Auth::user()->id;

        $agent_applies=AgentApply::where("user_id",$user_id)->get();
        return ApiResponse::makeResponse(true, $agent_applies, 200);
    }
	
}