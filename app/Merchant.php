<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected $primaryKey = 'mer_id';
	protected $table = 'merchant';
    protected $fillable = [
    	'mer_name','merchant_id','remit_public_key','remit_private_key','sign','version','mer_status','desc'
    ];

    
}
