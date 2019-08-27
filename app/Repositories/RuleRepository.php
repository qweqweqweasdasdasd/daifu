<?php

namespace App\Repositories;

use DB;
use App\Rule;

class RuleRepository extends BaseRepository
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->table = 'rule';
        $this->id = 'rule_id';    
    }

    /**
     * 层级的处理
     */
    public function LevelFormat($data)
    {
        if($data['pid'] != 0){
            $data['level'] = (Rule::where('rule_id',$data['pid'])->value('level') + 1);
        }
        return $data;
    }

    /**
     * 删除中间表通过权限id
     */
    public function DeleteRoleRuleByRuleId($id)
    {
        return DB::table('role_rule')->where('rule_id',$id)->delete();
    }
    /**
     * 获取到一级 二级权限
     */
    public function GetRuleLevel()
    {
        error_reporting(0);
        $rule_1 = json_decode(Rule::orderBy('rule_id','asc')->where('level',1)->get(),true);
        $rule_2 = json_decode(Rule::orderBy('rule_id','asc')->where('level',2)->get(),true);

        $data = [];
        foreach ($rule_1 as $k => $v) {
            $data[$k] = $v;
            foreach ($rule_2 as $kk => $vv) {
                if($vv['pid'] == $v['rule_id']){
                    $data[$k]['son'][$kk] = $vv;
                }
            }
        }
        return $data;
    }

    /**
     * 获取到所有的权限
     */
    public function GetRule()
    {
        $tree = GetTree(Rule::orderBy('rule_id','asc')->get()->toArray());
        
        foreach ($tree as $k => $v) {
            $tree[$k]['rule_name_text'] = str_repeat('|-',$v['level']) . $v['rule_name'];
        }
        return $tree;
    }

    /**
     * 获取到权限名称和权限id
     */
    public function GetRuleTree()
    {
        $tree = GetTree(Rule::orderBy('rule_id','asc')->get()->toArray());
         
        foreach ($tree as $k => $v) {
            $tree[$k]['rule_name_text'] = str_repeat('|-',$v['level']) . $v['rule_name'];
        }
        return $tree;
    }

    /**
     * 权限控制器
     */
    public function GetRuleC()
    {
        return Rule::whereNotNull('rule_c')
                    ->select('rule_c')
                    ->distinct()
                    ->get();
    }

    /**
     * 权限方法
     */
    public function GetRuleA()
    {
        return Rule::whereNotNull('rule_a')
                    ->select('rule_a')
                    ->distinct()
                    ->get();
    }
}