<?php

namespace App\Http\Controllers\Admin;

use App\Bank;
use App\ErrDesc\ApiErrDesc;
use Illuminate\Http\Request;
use App\Resphonse\JsonResphonse;
use App\Http\Requests\BankRequest;
use App\Http\Controllers\Controller;
use App\Repositories\BankRepository;

class BankController extends Controller
{
    /**
     * 银行仓库
     */
    protected $bank;

    /**
     * 初始化仓库
     */
    public function __construct(BankRepository $bank)
    {
        $this->bank = $bank;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $whereData = [
            'start' => !empty($request->get('start')) ? $request->get('start') :'',
            'end' => !empty($request->get('end')) ? $request->get('end') :'',
            'bankAccountNo' => !empty($request->get('bankAccountNo')) ? $request->get('bankAccountNo') :''
        ];
        $pathInfo = $this->bank->getCurrentPathInfo();
        $getBank = $this->bank->GetBank($whereData);
        
        return view('admin.bank.index',compact('pathInfo','getBank','whereData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bank_list = Bank::BANK_LIST;
        
        return view('admin.bank.create',compact('bank_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankRequest $request)
    {
        if(!$this->bank->CommonSave($request->all())){
            return JsonResphonse::JsonData(ApiErrDesc::BANK_SAVE_FAIL[0],ApiErrDesc::BANK_SAVE_FAIL[1]);
        };
        return JsonResphonse::ResphonseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $status = $request->get('status');
        //dd($status);
        if(!$this->bank->CommonUpdateStatus($id,$status)){
            return JsonResphonse::JsonData(ApiErrDesc::UPDATE_STATUS_FAIL[0],ApiErrDesc::UPDATE_STATUS_FAIL[1]);
        };
        return JsonResphonse::ResphonseSuccess();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bank_list = Bank::BANK_LIST;
        $bank = $this->bank->CommonFirst($id);
        //dump($bank);
        return view('admin.bank.edit',compact('bank_list','bank'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [
            "bankCode" => !empty($request->get('bankCode')) ? $request->get('bankCode') :'',
            "bankAccountNo" => !empty($request->get('bankAccountNo')) ? $request->get('bankAccountNo') :'',
            "bankAccountName" => !empty($request->get('bankAccountName')) ? $request->get('bankAccountName') :'',
            "bank_status" => !empty($request->get('bank_status')) ? $request->get('bank_status') :'',
            "remarks" => !empty($request->get('remarks')) ? $request->get('remarks') :''
        ];
        if(!$this->bank->CommonUpdate($id,$data)){
            return JsonResphonse::JsonData(ApiErrDesc::BANK_UPDATE_FAIL[0],ApiErrDesc::BANK_UPDATE_FAIL[1]);
        };
        return JsonResphonse::ResphonseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$this->bank->CommonDelete($id)){
            return JsonResphonse::JsonData(ApiErrDesc::BANK_UPDATE_FAIL[0],ApiErrDesc::BANK_UPDATE_FAIL[1]);
        };
        return JsonResphonse::ResphonseSuccess();
    }

    /**
     * 获取到指定的一条银行信息
     */
    public function getOne($id)
    {
        $bank = $this->bank->CommonFirst($id);
        
        return JsonResphonse::ResphonseSuccess(json_encode($bank));
    }
}
