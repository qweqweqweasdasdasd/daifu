<?php

namespace App\Http\Controllers\Admin;

use Google2FA;
use App\Manager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoogleTokenController extends Controller
{
    /**
     * 显示管理员列表
     */
    public function ShowManager()
    {
        $managers = Manager::where('mg_status','1')->get();
        //dump($managers);
        return view('admin.google.token.show',compact('managers'));
    }

    /**
     * 渲染激活视图或者禁用视图
     */
    public function GoogleToken(Request $request)
    {
        $mg_id = $request->get('mg_id');
        // 用户是否存在google_token
        $user = Manager::where('mg_id',$mg_id)->first();
        //dd($user);
        return $this->showEnableTokenForm($user);
    }
    
    /**
     * 显示激活视图
     */
    public function showEnableTokenForm($user)
    {
        
        $key = Google2FA::generateSecretKey(64);
        $user->google_token = $key;
        $user->save();
        $google2fa_url = Google2FA::getQRCodeInline(
            env('APP_NAME'),
            $user->mg_name,
            $key
        );
        return [
            'code'=>1,
            'data'=>[
                'user' => $user,
                'key' => $key,
                'QRCode' => $google2fa_url
            ]
        ];
    }
}
