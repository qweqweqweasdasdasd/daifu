<?php

namespace App\Http\Controllers\Admin;

use Google2FA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoogleTokenController extends Controller
{
    /**
     * 渲染激活视图或者禁用视图
     */
    public function GoogleToken()
    {
        // 用户是否存在google_token
        $user = [
            'email' => 'Qq88678867@gmail.com'
        ];
        return $this->showEnableTokenForm($user);
    }

    /**
     * 显示激活视图
     */
    public function showEnableTokenForm($user)
    {
        $key = Google2FA::generateSecretKey(64);

        $google_url = Google2FA::getQRCodeGoogleUrl(
            'Application Name',
            $user['email'],
            $key
        );
        
        dd($google_url);
        return view('admin.google.token.enable',[
            'user' => $user,
            'key' => $key,
            'QRCode' => $google_url
        ]);
    }
}
