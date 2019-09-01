<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Google2FA;
use App\ErrDesc\ApiErrDesc;
use Illuminate\Http\Request;
use App\Resphonse\JsonResphonse;
use Illuminate\Support\Facades\Hash;
use App\Repositories\RoleRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\ManagerRequest;
use App\Http\Requests\UnboundRequest;
use App\Repositories\ManagerRepository;

class ManagerController extends Controller
{
    /**
     * 管理员仓库
     */
    protected $manager;

    /**
     * 角色仓库
     */
    protected $role;

    /**
     * 初始化仓库
     */
    public function __construct(ManagerRepository $manager,RoleRepository $role)
    {
        $this->manager = $manager;
        $this->role = $role;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $whereData = [
            'mg_name' => !empty($request->get('mg_name'))? trim($request->get('mg_name')) :'',
            'start' => !empty($request->get('start'))? trim($request->get('start')) :'',
            'end' => !empty($request->get('end'))? trim($request->get('end')) :''
        ];

        $pathInfo = $this->manager->getCurrentPathInfo();
        $getManagers = $this->manager->GetManagers($whereData);
        //dump($whereData);
        return view('admin.manager.index',compact('pathInfo','getManagers','whereData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roleNameIdStatus = $this->role->GetRoleNameIdStatus();
        //dump($roleNameIdStatus);
        return view('admin.manager.create',compact('roleNameIdStatus'));
    }

    /**
     * 新建管理员 && 管理员和角色关系
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ManagerRequest $request)
    {
        $data = [
            'mg_name' => $request->get('mg_name'),
            'password' => Hash::make($request->get('password')),
            'mg_status' => $request->get('mg_status'),
            'mg_email' => $request->get('mg_email'),
        ];

        // 事务
        \DB::beginTransaction();
        try {
            $manager = $this->manager->ManagerSave($data);
            $manager->roles()->sync($request->get('role_ids'));
            \DB::commit();
            return JsonResphonse::ResphonseSuccess();
        } catch (\Exception $e) {
            \DB::rollBack();
            return JsonResphonse::JsonData(ApiErrDesc::MANAGER_SAVE_FAIL[0],ApiErrDesc::MANAGER_SAVE_FAIL[1]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $status = $request->get('status');

        if(!$this->manager->CommonUpdateStatus($id,$status)){
            return JsonResphonse::JsonData(ApiErrDesc::UPDATE_STATUS_FAIL[0],ApiErrDesc::UPDATE_STATUS_FAIL[1]);
        };
        return JsonResphonse::ResphonseSuccess();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $manager = $this->manager->ManagerWithRelation($id);
        $roleNameIdStatus = $this->role->GetRoleNameIdStatus();
        
        return view('admin.manager.edit',compact('manager','roleNameIdStatus'));
    }

    /**
     * 更新管理员 && 管理员和角色关系
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [
            'mg_name' => $request->get('mg_name'),
            'mg_status' => $request->get('mg_status'),
            'mg_email' => $request->get('mg_email'),
        ];

        // 事务
        \DB::beginTransaction();
        try {
            $manager = $this->manager->ManagerUpdate($id,$data);
            $manager->roles()->sync($request->get('role_ids'));
            \DB::commit();
            return JsonResphonse::ResphonseSuccess();
        } catch (\Exception $e) {
            \DB::rollBack();
            return JsonResphonse::JsonData(ApiErrDesc::MANAGER_UPDATE_FAIL[0],ApiErrDesc::MANAGER_UPDATE_FAIL[1]);
        }
    }

    /**
     * 删除管理员 && 管理员和角色关系
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::beginTransaction();
        try {
            // 删除管理员
            $this->manager->CommonDelete($id);
            // 删除管理员和角色关系
            $this->manager->ManagerRoleDelete($id);
            \DB::commit();
            return JsonResphonse::ResphonseSuccess();
        } catch (\Exception $th) {
            \DB::rollBack();
            return JsonResphonse::JsonData(ApiErrDesc::MANAGER_DELETED_FAIL[0],ApiErrDesc::MANAGER_DELETED_FAIL[1]);
        }
    }

    /**
     * 管理员重绑二次验证
     */
    public function unbound(Request $request)
    {
        if($request->isMethod('post')){
            // 管理员不得为空
            $mgId = $request->get('mg_id');
            $gooleToken = $request->get('gooleToken');

            // 管理员不得为空(格式不对)
            if(is_null($mgId) || !is_array($mgId)){
                return JsonResphonse::JsonData(ApiErrDesc::MANAGER_ID_FAIL[0],ApiErrDesc::MANAGER_ID_FAIL[1]);
            }
            
            // 二次验证不得为空或者不为6位数字
            if(is_null($gooleToken) || !is_numeric($gooleToken) || strlen($gooleToken) != 6){
                return JsonResphonse::JsonData(ApiErrDesc::MANAGER_GOOLE_TOKEN_FAIL[0],ApiErrDesc::MANAGER_GOOLE_TOKEN_FAIL[1]);
            }
            
            // 超级管理员谷歌验证码
            $root = $this->manager->ManagerFirst(1);
            $secretKey = $root->google_token;
            // 谷歌账号没有绑定
            if(!$secretKey){
                return JsonResphonse::JsonData(ApiErrDesc::GOOLE_BINDING_NO[0],ApiErrDesc::GOOLE_BINDING_NO[1]);
            }

            $verify = Google2FA::verifyKey($secretKey, $request->input('gooleToken'));
            if(!$verify){
                // 谷歌二次验证失败
                return JsonResphonse::JsonData(ApiErrDesc::GOOLE_VERIFY_FAIL[0],ApiErrDesc::GOOLE_VERIFY_FAIL[1]);
            }

            // 清空谷歌令牌
            $mgIds = $request->get('mg_id');
            foreach ($mgIds as $k => $v) {
                $manager = $this->manager->ManagerFirst($v);
                $manager->google_token = '';
                $manager->save();
            }
            return JsonResphonse::ResphonseSuccess();

        }
        $GetManagerIdName = $this->manager->GetManagerIdName();
        return view('admin.manager.unbound',compact('GetManagerIdName'));
    }

    /**
     * 管理员密码重置
     */
    public function reset(Request $request)
    {
        if($request->isMethod('post')){

            if( empty($request->get('old_password')) || empty($request->get('password')) || empty($request->get('password_confirmation')) ){
                return JsonResphonse::JsonData(ApiErrDesc::MANAGER_PASSWORD_NOT_EMPTY[0],ApiErrDesc::MANAGER_PASSWORD_NOT_EMPTY[1]);
            }

            $manager = Auth::guard('admin')->user();
            if(!Hash::check($request->get("old_password"),$manager->password)){
                return JsonResphonse::JsonData(ApiErrDesc::MANAGER_OLD_PASSWORD_ERROR[0],ApiErrDesc::MANAGER_OLD_PASSWORD_ERROR[1]);
            }

            if($request->get('password_confirmation') != $request->get('password')){
                return JsonResphonse::JsonData(ApiErrDesc::MANAGER_OLD_NEW_UNLIKE[0],ApiErrDesc::MANAGER_OLD_NEW_UNLIKE[1]);
            }

            $manager->password = Hash::make($request->get('password'));
            $manager->save();
            return JsonResphonse::ResphonseSuccess();
        }
        return view('admin.manager.reset');
    }
}
