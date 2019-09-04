<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recheck extends Model
{
    protected $primaryKey = 'recheck_id';
	protected $table = 'recheck';
    protected $fillable = [
    	'desc','recheck_status','re_operator','merchant_id'
    ];

}
