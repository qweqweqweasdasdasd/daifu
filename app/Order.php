<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $primaryKey = 'order_id';
	protected $table = 'order';
    protected $fillable = [
    	'merOrderNo','amount','remarks','operator','bank_info','order_status','merchant_id'
    ];

    /**
     * 订单状态
     */

    const XIAFA_SUBMIT = ['1','下发提交'];

    const CHECKING = ['2','接口维护中'];
    
    const XIAFA_IMG = ['6','下发处理中'];

    const XIAFA_SUCCESS = ['3','下发成功'];

    const XIAFA_FAIL = ['4','下发失败'];

    const ORDER_GUOQI = ['5','订单过期'];

}
