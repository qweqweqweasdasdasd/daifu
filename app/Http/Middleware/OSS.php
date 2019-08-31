<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class OSS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 获取session id
        $sessionId = $request->session()->getId();
        $manager = \Auth::guard('admin')->user();

        // 判断数据库内是否存在了sessionID
        if(!($manager->session_id)){
            $manager->session_id = $sessionId;
            $manager->update();
        }
        
        //判断数据库内和登录的sessionid是否一致
        if($manager->session_id != $sessionId){
            try {
                // 删除旧的sessionid文件
                $session_file = storage_path() . '/framework/sessions/' . $manager->session_id;
                @unlink($session_file);
                $manager->session_id = $sessionId;
                $manager->update();
            } catch (\Exception $th) {
                
            }
        }
        
        return $next($request);
    }
}
