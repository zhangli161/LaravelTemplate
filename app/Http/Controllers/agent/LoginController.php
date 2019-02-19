<?php

namespace App\Http\Controllers\Agent;

use App\Components\QRManager;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    //
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/agent/index';
    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:agent', ['except' => 'logout']);
        $this->username = "name";
    }

    /**
     * 重写登录视图页面
     */
    public function showLogin($message=null)
    {
        return view('agent.login',["message"=>$message]);
    }

    /**
     * 自定义认证驱动
     * @return mixed
     */
    protected function guard()
    {
        return auth()->guard('agent');
    }

    public function login(Request $request) {
        $user = $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'required|min:5',
        ]);
        if (Auth::guard('agent')->attempt($user)) { // 登陆验证
            $agent=Agent::where("name",$user['name'])->first();
            if (empty($agent->xcx_qr)){
                $qr = QRManager::getAgentXCXQR($agent->id);
                $agent->xcx_qr = $qr;
                $agent->save();
            }

            return redirect()->to('agent');
        } else {
            return $this->showLogin("用户名或密码错误，请重试");
        }
    }




}