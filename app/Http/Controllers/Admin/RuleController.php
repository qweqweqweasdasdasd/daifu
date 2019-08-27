<?php

namespace App\Http\Controllers\Admin;

use App\ErrDesc\ApiErrDesc;
use Illuminate\Http\Request;
use App\Resphonse\JsonResphonse;
use App\Http\Requests\RuleRequest;
use App\Http\Controllers\Controller;
use App\Repositories\RuleRepository;

class RuleController extends Controller
{
    /**
     * 权限仓库
     */
    protected $rule;

    /**
     * 初始化仓库
     */
    public function __construct(RuleRepository $rule)
    {
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
            'contrller' => !empty($request->get('contrller')) ? $request->get('contrller') :'',
            'action' => !empty($request->get('action')) ? $request->get('action') :'',
        ];
        //dump($whereData);

        $pathInfo = $this->rule->getCurrentPathInfo();
        $getRule = $this->rule->GetRule();
        $getRuleC  = $this->rule->GetRuleC();
        $getRuleA  = $this->rule->GetRuleA();
        
        //dd($getRuleC);
        return view('admin.rule.index',compact('pathInfo','getRule','getRuleC','getRuleA','whereData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $getRuletree = $this->rule->GetRuleTree();
        
        return view('admin.rule.create',compact('getRuletree'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RuleRequest $request)
    {
        // 层级处理
        $data = $this->rule->LevelFormat($request->all());
        
        if(!$this->rule->CommonSave($data)){
            return JsonResphonse::JsonData(ApiErrDesc::RULE_SAVE_FAIL[0],ApiErrDesc::RULE_SAVE_FAIL[1]);
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
        $getRuletree = $this->rule->GetRuleTree();
        $rule = $this->rule->CommonFirst($id);
        //dump($rule);
        return view('admin.rule.edit',compact('getRuletree','rule'));
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
        // 层级处理
        $data = $this->rule->LevelFormat($request->all());
        unset($data['rule_id']);
        if(!$this->rule->CommonUpdate($id,$data)){
            return JsonResphonse::JsonData(ApiErrDesc::RULE_DELETED_FAIL[0],ApiErrDesc::RULE_DELETED_FAIL[1]);
        };
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
            // 删除权限
            $this->rule->CommonDelete($id);
            // 删除权限中间表
            $this->rule->DeleteRoleRuleByRuleId($id);
            \DB::commit();
            return JsonResphonse::ResphonseSuccess();
        } catch (\Exception $e) {
            \DB::rollback();
            return JsonResphonse::JsonData(ApiErrDesc::MANAGER_DELETED_FAIL[0],ApiErrDesc::MANAGER_DELETED_FAIL[1]);
        }
    }

    /**
     * 修改是否显示和是否验证接口
     */
    public function switch(Request $request, $param)
    {
        $d = $request->get('d');
        $id = $request->get('id');
        if($param == 'is_show'){
            $data = ['is_show'=>$d];
        }
        if($param == 'is_verify'){
            $data = ['is_verify'=>$d];
        }
        if(!$this->rule->CommonUpdate($id,$data)){
            return JsonResphonse::JsonData(ApiErrDesc::RULE_UPDATE_FAIL[0],ApiErrDesc::RULE_UPDATE_FAIL[1]);
        };
        return JsonResphonse::ResphonseSuccess($param);
    }
}
