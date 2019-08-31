<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Manager extends Authenticatable
{
    protected $primaryKey = 'mg_id';
	protected $table = 'manager';
    protected $fillable = [
    	'mg_name','password','mg_status','mg_email','login_count','last_login_time','last_login_ip','session_id','google_token'
    ];

    protected $rememberTokenName = '';


    /**
     * (激活||停用) 状态
     */
    public function MgStatus()
    {
        return !!($this->mg_status == 1);
    }

    /**
     * 角色多对多关系
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role','manager_role','mg_id','role_id')->withTimestamps();
    }
}
