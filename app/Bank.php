<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $primaryKey = 'bank_id';
	protected $table = 'bank';
    protected $fillable = [
    	'bankCode','bankAccountNo','bankAccountName','bank_status','remarks'
    ];

    /**
     * 银行列表
     */
    const BANK_LIST = [
        '工商银行' =>	'ICBC',
        '建设银行' =>	'CCB',
        '农业银行' =>	'ABC',
        '邮政储蓄银行' =>	'PSBS',
        '中国银行' =>	'BOC',
        '交通银行' =>	'BOCO',
        '招商银行' =>	'CMB',
        '光大银行' =>	'CEB',
        '兴业银行' =>	'CIB',
        '民生银行' =>	'CMBC',
        '北京银行' =>	'BCCB',
        '中信银行' =>	'CTTIC',
        '广东发展银行' =>	'GDB',
        '深圳发展银行' =>	'SDB',
        '浦东发展银行' =>	'SPDB',
        '平安银行' =>	'PINGANBANK',
        '华夏银行' =>	'HXB',
        '上海银行' =>	'SHB',
        '渤海银行' =>	'CBHB',
        '东亚银行' =>	'HKBEA',
        '宁波银行' =>	'NBCB',
        '浙商银行' =>	'CZB',
        '南京银行' =>	'NJCB',
        '杭州银行' =>	'HZCB',
        '北京农村商业银行' =>	'BJRCB',
        '上海农商银行' =>	'SRCB'
    ];
}
