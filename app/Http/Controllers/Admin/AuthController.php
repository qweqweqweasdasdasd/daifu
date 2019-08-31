<?php

namespace App\Http\Controllers\Admin;

use Google2FA;
use App\Manager;
use App\ErrDesc\ApiErrDesc;
use Illuminate\Http\Request;
use App\Resphonse\JsonResphonse;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * 二次验证开关
     */
    protected $gooleVerify = false;
    /**
     * 显示登录页面
     */
    public function login()
    {
        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.index');
        }
        return view('admin.auth.login');
    }

    /**
     * 登录动作
     */
    public function DoLogin(AuthRequest $request)
    {
        // 管理员认证
        $user_password = $request->only(['mg_name','password']);
        if(!Auth::guard('admin')->attempt($user_password)){
            // 管理员认证失败
            return JsonResphonse::JsonData(ApiErrDesc::USER_AUTH_FAIL[0],ApiErrDesc::USER_AUTH_FAIL[1]);
        };
        
        // 检查管理状态 (中间件)
        if(!Auth::guard('admin')->user()->MgStatus()){
            // 管理员状态停用
            return JsonResphonse::JsonData(ApiErrDesc::USER_BAN_STATUS[0],ApiErrDesc::USER_BAN_STATUS[1]);
        };

        // 谷歌二次验证     
        if($this->gooleVerify){
            $secretKey = (Auth::guard('admin')->user()->google_token);
            
            if(!$secretKey){
                Auth::guard('admin')->logout();
                return JsonResphonse::JsonData(ApiErrDesc::GOOLE_BINDING_NO[0],ApiErrDesc::GOOLE_BINDING_NO[1]);
            }
            $verify = Google2FA::verifyKey($secretKey, $request->input('gooleToken'));
            if(!$verify){
                Auth::guard('admin')->logout();
                return JsonResphonse::JsonData(ApiErrDesc::GOOLE_VERIFY_FAIL[0],ApiErrDesc::GOOLE_VERIFY_FAIL[1]);
            }
        }
        
        // 记录登录次数,时间,ip
        $data = [
            'login_count' => ++Auth::guard('admin')->user()->login_count,
            'last_login_time' => date('Y-m-d H:i:s',time()),
            'last_login_ip' => $request->getClientIp()
        ];
        Auth::guard('admin')->user()->update($data);

        $resphonse = [
            'href' => '/admin/index'
        ];
        return JsonResphonse::ResphonseSuccess($resphonse);
    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        return redirect()->route('login');
    }
}
