<?php

namespace App\Repositories;

use DB;
use Illuminate\Support\Facades\Route;

class BaseRepository
{
    /**
     * 表名
     */
    protected $table;

    /**
     * 主键
     */
    protected $id;

    /**
     * 公共新建方法
     */
    public function CommonSave($data)
    {
        $data['created_at'] = NowTime();
        return DB::table($this->table)->insert($data); 
    }

    /**
     * 公共获取指定id方法
     */
    public function CommonFirst($id)
    {
        return DB::table($this->table)->where($this->id,$id)->first();
    }

    /**
     * 公共删除方法
     */
    public function CommonDelete($id)
    {
        return DB::table($this->table)->where($this->id,$id)->delete();
    }

    /**
     * 公共更新方法
     */
    public function CommonUpdate($id,$data)
    {
        $data['updated_at'] = NowTime();
        return DB::table($this->table)->where($this->id,$id)->update($data);
    }

    /**
     * 公共编辑状态方法
     */
    public function CommonUpdateStatus($id,$status)
    {
        $field = $this->table.'_status';
        if($this->table == 'manager'){
            $field = 'mg_status';    
        }
        if($this->table == 'merchant'){
            $field = 'mer_status';
        }
        $data = [$field => $status];
        return $this->CommonUpdate($id,$data);
    }

    /**
     * 
     */

    /**
     * 获取到当前的 模块||控制器||方法
     */
    public function getCurrentPathInfo()
    {
        $action = Route::current()->getActionName();

        list($class,$method) = explode('@',$action);
        $arr = explode('\\',strtolower($class));
        $cu = str_replace('controller','',$arr[4]);
        $module = $arr[3];
        
        return ['module'=>$module,'cu'=>$cu,'method'=>$method];
    }

}