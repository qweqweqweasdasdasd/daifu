<?php

namespace App\Http\Controllers\Admin;

use App\ErrDesc\ApiErrDesc;
use Illuminate\Http\Request;
use App\Resphonse\JsonResphonse;
use App\Http\Controllers\Controller;
use App\Http\Requests\MerchantRequest;
use App\Repositories\MerchantRepository;

class MerchantController extends Controller
{
    /**
     * 代付提供商版本号
     */
    protected $version = '1.1';
    /**
     * 复审仓库
     */
    protected $merchant;

    /**
     * 初始化仓库
     */
    public function __construct(MerchantRepository $merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $whereData = [
            'start' => !empty($request->get('start'))? $request->get('start') :'',
            'end' => !empty($request->get('end'))? $request->get('end') :'',
            'mer_status' => !empty($request->get('mer_status'))? $request->get('mer_status') :'',
            'key' => !empty($request->get('key'))? $request->get('key') :''
        ];
        //dump($whereData);
        $pathInfo = $this->merchant->getCurrentPathInfo();
        $merchants = $this->merchant->Merchants($whereData);
        foreach ($merchants as $k => $v) {
            $v->sign = str_hide($v->sign,10,12);
        }
        return view('admin.merchant.index',compact('pathInfo','merchants','whereData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.merchant.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantRequest $request)
    {
        $data = [
            'mer_name' => trim($request->get('mer_name')),
            'merchant_id' => trim($request->get('merchant_id')),
            'sign' => trim($request->get('sign')),
            'version' => $this->version,
            'desc' => trim($request->get('desc')),
        ];

        if(!$this->merchant->CommonSave($data)){
            return JsonResphonse::JsonData(ApiErrDesc::MERCHANT_SAVA_FAIL[0],ApiErrDesc::MERCHANT_SAVA_FAIL[1]);
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
        if(!$this->merchant->CommonUpdateStatus($id,$status)){
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
        $merchant = $this->merchant->CommonFirst($id);
        
        return view('admin.merchant.edit',compact('merchant'));
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
            'mer_name' => trim($request->get('mer_name')),
            'merchant_id' => trim($request->get('merchant_id')),
            'sign' => trim($request->get('sign')),
            'mer_status' => trim($request->get('mer_status')),
            'desc' => trim($request->get('desc'))
        ];
        
        if(!$this->merchant->CommonUpdate($id,$data)){
            return JsonResphonse::JsonData(ApiErrDesc::MERCHANT_UPDATE_FAIL[0],ApiErrDesc::MERCHANT_UPDATE_FAIL[1]);
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
        if(!$this->merchant->CommonDelete($id)){
            return JsonResphonse::JsonData(ApiErrDesc::MERCHANT_DELETED_FAIL[0],ApiErrDesc::MERCHANT_DELETED_FAIL[1]);
        };
        return JsonResphonse::ResphonseSuccess();
    }

    /**
     * 配置公私钥view
     */
    public function deploy($merid)
    {
        $merchant = $this->merchant->CommonFirst($merid);
        return view('admin.merchant.deploy',compact('merid','merchant'));
    }

    /**
     * 配置公私钥
     */
    public function doDeploy(Request $request)
    {
        $merid = $request->get('merid');
        $data = [
            'remit_public_key' => trim($request->get('remit_public_key')),
            'remit_private_key' => trim($request->get('remit_private_key'))
        ];
        if(!$this->merchant->CommonUpdate($merid,$data)){
            return JsonResphonse::JsonData(ApiErrDesc::PUBLIC_PRIVATE_UPDATE_FAIL[0],ApiErrDesc::PUBLIC_PRIVATE_UPDATE_FAIL[1]);
        };
        return JsonResphonse::ResphonseSuccess();
    }
}
