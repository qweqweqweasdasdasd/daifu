<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $primaryKey = 'rule_id';
	protected $table = 'rule';
    protected $fillable = [
    	'rule_name','route','rule_c','rule_a','level','is_show','is_verify','pid','remark'
    ];
}
