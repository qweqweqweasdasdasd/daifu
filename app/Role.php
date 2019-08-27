<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'role_id';
	protected $table = 'role';
    protected $fillable = [
    	'r_name','role_status','remark'
    ];

    // 多对多关系
    public function rules()
    {
        return $this->belongsToMany('App\Rule','role_rule','role_id','rule_id')->withTimestamps();
    }
}
