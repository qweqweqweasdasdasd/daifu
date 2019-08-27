<?php

namespace App\Repositories;

use DB;
use App\Bank;

class BankRepository extends BaseRepository
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->table = 'bank';
        $this->id = 'bank_id';    
    }

    const ENABLED = '1';        // 启用

    const DISABLE = '0';        // 停用
    /**
     * 获取到所有的银行信息
     */
    public function GetBank($d)
    {
        return Bank::where(function($query) use($d){
                        if( !empty($d['bankAccountNo']) ){
                            $query->where('bankAccountNo',$d['bankAccountNo']);
                        } 
                        if( !empty($d['start']) && !empty($d['end']) &&  $d['end'] >= $d['start']){
                            $query->whereBetween('created_at',[$d['start'],$d['end']] );
                        }
                    })
                    ->orderBy('bank_id','asc')
                    ->paginate(9);
    }

    /**
     * 获取到银行号码和持卡人 启用的
     */
    public function GetBankAccountNoName()
    {
        return Bank::where('bank_status',self::ENABLED)
                ->get([
                        'bankAccountNo',
                        'bankAccountName',
                        'bank_status',
                        'bank_id',
                        'bankCode'
                    ]);
    }
}