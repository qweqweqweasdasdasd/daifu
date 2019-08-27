<?php

namespace App\Repositories;

use DB;
use App\Role;

class RoleRepository extends BaseRepository
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->table = 'role';
        $this->id = 'role_id';    
    }

    /**
     * 删除管理员和角色关系表
     */
    public function ManagerRoleDelete($id)
    {
        return DB::table('manager_role')->where('role_id',$id)->delete();
    }

    /**
     * 删除角色和权限关系
     */
    public function RuleRoleDelete($id)
    {
        return DB::table('role_rule')->where('role_id',$id)->delete();
    }
    /**
     * 获取到权限ids
     */
    public function GetHasRules($rules)
    {
        $data = [];
        foreach ($rules as $k => $v) {
            $data[$k] = $v->rule_id;
        }
        return $data;
    }
    /**
     * 获取指定的角色
     */
    public function Role($id)
    {
        return Role::with('rules')->find($id);
    }
    /**
     * 获取到所有的角色
     */
    public function GetRole($d)
    {
        return DB::table($this->table)
                ->where(function($query) use($d){
                    if( !empty($d['r_name']) ){
                        $query->where('r_name',$d['r_name']);
                    } 
                    if( !empty($d['start']) && !empty($d['end']) &&  $d['end'] >= $d['start']){
                        $query->whereBetween('created_at',[$d['start'],$d['end']] );
                    }
                })
                ->orderBy('role_id','asc')
                ->paginate(9);
    }

    /**
     * 获取角色名称,id,状态
     */
    public function GetRoleNameIdStatus()
    {
        return DB::table($this->table)->select('role_id','r_name','role_status')->orderBy('role_id','asc')->get();
    }
}