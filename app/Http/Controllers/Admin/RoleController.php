<?php

namespace App\Http\Controllers\Admin;

use App\ErrDesc\ApiErrDesc;
use Illuminate\Http\Request;
use App\Resphonse\JsonResphonse;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use App\Repositories\RuleRepository;

class RoleController extends Controller
{
    /**
     * 角色仓库
     */
    protected $role;

    /**
     * 权限仓库
     */
    protected $rule;

    /**
     * 初始化仓库
     */
    public function __construct(RoleRepository $role,RuleRepository $rule)
    {
        $this->role = $role;
        $this->rule = $rule;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $whereData = [
            'r_name' => !empty($request->get('r_name'))? trim($request->get('r_name')) :'',
            'start' => !empty($request->get('start'))? trim($request->get('start')) :'',
            'end' => !empty($request->get('end'))? trim($request->get('end')) :''
        ];
        
        $pathInfo = $this->role->getCurrentPathInfo();
        $getRole = $this->role->GetRole($whereData);
        //dump($getRole);
        return view('admin.role.index',compact('pathInfo','getRole','whereData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        if(!$this->role->CommonSave($request->all())){
            return JsonResphonse::JsonData(ApiErrDesc::ROLE_SAVE_FAIL[0],ApiErrDesc::ROLE_SAVE_FAIL[1]);
        };
        return JsonResphonse::ResphonseSuccess();
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

        if(!$this->role->CommonUpdateStatus($id,$status)){
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
        $role = $this->role->CommonFirst($id);
        //dump($role);
        return view('admin.role.edit',compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = $request->get('role_id');
        $data = [
            'r_name' => $request->get('r_name'),
            'role_status' => $request->get('role_status'),
            'remark' => $request->get('remark')
        ];
        if(!$this->role->CommonUpdate($id,$data)){
            return JsonResphonse::JsonData(ApiErrDesc::ROLE_UPDATE_FAIL[0],ApiErrDesc::ROLE_UPDATE_FAIL[1]);
        }
        return JsonResphonse::ResphonseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::beginTransaction();
        try {
            // 删除角色 
            $this->role->CommonDelete($id);
            // 删除角色和管理员关系
            $this->role->ManagerRoleDelete($id);
            // 删除角色和权限关系
            $this->role->RuleRoleDelete($id);
            \DB::commit();
            return JsonResphonse::ResphonseSuccess();
        } catch (\Exception $e) {
            \DB::rollback();
            return JsonResphonse::JsonData(ApiErrDesc::ROLE_DELETED_FAIL[0],ApiErrDesc::ROLE_DELETED_FAIL[1]);
        }
    }

    /**
     * 给角色权限
     */
    public function assign(Request $request, $id)
    {
        // 获取到改角色权限
        $role = $this->role->Role($id);
        
        if($request->isMethod('post')){
            // 操作中间表
            $role->rules()->sync($request->get('rule_id'));
            return JsonResphonse::ResphonseSuccess();
        }
        // 获取到1级,2级,权限
        $rules = $this->rule->GetRuleLevel();
        $has_rules = $this->role->GetHasRules($role->rules);
        //dump($has_rules);
        return view('admin.role.assign',compact('role','rules','has_rules'));
    }
}
