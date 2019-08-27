<?php

namespace App\Repositories;

use DB;
use App\Manager;

class ManagerRepository extends BaseRepository
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->table = 'manager';
        $this->id = 'mg_id';    
    }

    /**
     * 获取到所有的管理员信息
     */
    public function GetManagers($d)
    {
        return Manager::with(['roles' => function($query){
                    $query->select('r_name','role_status');
                }])
                ->where(function($query) use($d){
                    if( !empty($d['mg_name']) ){
                        $query->where('mg_name',$d['mg_name']);
                    } 
                    if( !empty($d['start']) && !empty($d['end']) &&  $d['end'] >= $d['start']){
                        $query->whereBetween('created_at',[$d['start'],$d['end']] );
                    }
                })
                ->orderBy('mg_id','asc')
                ->paginate(9);
    }

    /**
     * 创建新管理员数据返回id
     */
    public function ManagerSave($data)
    {
        return Manager::create($data);
    }

    /**
     * 更新管理员数据返回id
     */
    public function ManagerUpdate($id,$d)
    {
        $manager = Manager::find($id);
        $manager->mg_name = $d['mg_name'];
        $manager->mg_status = $d['mg_status'];
        $manager->mg_email = $d['mg_email'];
        $manager->save();
        return $manager; 
    }

    /**
     * 删除管理员和角色关系表
     */
    public function ManagerRoleDelete($id)
    {
        return DB::table('manager_role')->where('mg_id',$id)->delete();
    }

    /**
     * 获取到一条管理员信息和中间表
     */
    public function ManagerWithRelation($id)
    {
        $manager = $this->CommonFirst($id);
        $role_ids =  json_decode(DB::table('manager_role')->where('mg_id',$id)->get(['role_id']),true);

        $role_ids_arr = [];
        foreach($role_ids as $k=>$v){
            $role_ids_arr[$k] = $v['role_id'];
        }
        $manager->role_ids_arr = is_array($role_ids_arr) ? $role_ids_arr :[];
        return $manager;
    }

    /**
     * 获取到管理员id和名字
     */
    public function GetManagerIdName()
    {
        return Manager::pluck('mg_name','mg_id')->toArray();
    }

}