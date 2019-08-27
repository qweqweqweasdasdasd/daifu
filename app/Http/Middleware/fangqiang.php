<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Rule;

class fangqiang
{
    /**
     * 通用权限
     */
    protected $CommonRule = [
        'index-index',
        'index-welcome',

    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $manager = Auth::guard('admin')->user();
        
        // id 为1是超级管理员
        if($manager->mg_id != 1){
            $roles = $manager->roles;
            $ruleCa = [];
            foreach ($roles as $k => $role) {
                $rules = $role->rules;
                foreach($rules as $kk => $rule){
                    $ruleCa[] = strtolower($rule->rule_c . '-' . $rule->rule_a);
                }
            }
    
            // 不用验证的权限
            $rule = Rule::select(\DB::raw('concat(rule_c,"-",rule_a) as ca'))->where('is_verify',2)->whereNotNull('rule_c')->get(['ca']);
            $allow = [];
            foreach ($rule as $k => $r) {
                $allow[] = $r->ca;
            }
            $ruleCa = array_merge($ruleCa,$this->CommonRule,$allow);
            
            $nowCa = strtolower(getCurrentControllerName().'-'.getCurrentMethodName());
            //删除重复的数值切换为字符串
            $ca = implode(',',array_unique($ruleCa));
            if(strpos($ca,$nowCa) === false){
                exit('没有权限!');
            } 
        }
        
        return $next($request);
    }
}
