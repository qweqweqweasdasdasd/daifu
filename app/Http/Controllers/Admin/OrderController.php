<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\ErrDesc\ApiErrDesc;
use Illuminate\Http\Request;
use App\Resphonse\JsonResphonse;
use App\Http\Requests\OrderRequest;
use App\Http\Controllers\Controller;
use App\Repositories\BankRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RecheckRepository;
use App\Repositories\ManagerRepository;

class OrderController extends Controller
{
    /**
     * 下发订单仓库
     */
    protected $order;

    /**
     * 银行仓库
     */
    protected $bank;

    /**
     * 复审仓库
     */
    protected $recheck;

    /**
     * 初始化仓库
     */
    public function __construct(OrderRepository $order,BankRepository $bank,ManagerRepository $manager,RecheckRepository $recheck)
    {
        $this->order = $order;
        $this->bank  = $bank;
        $this->manager = $manager;
        $this->recheck = $recheck;
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
            'order_status' => !empty($request->get('order_status'))? $request->get('order_status') :'',
            'merOrderNo' => !empty($request->get('merOrderNo'))? $request->get('merOrderNo') :''
        ];
        
        $pathInfo = $this->order->getCurrentPathInfo();
        $getOrder = $this->order->GetOrder($whereData);
        $getManagerIdName = $this->manager->GetManagerIdName();
        
        foreach($getOrder as $v){
           $v->operator = $getManagerIdName[$v->operator];
           $json =  json_decode($v->bank_info);

           $v->bank_info = '银行编号: ' . $json->bankCode . ' 银行卡账号: ' . $json->bankAccountNo . ' 持卡姓名: ' . $json->bankAccountName;
        }
        //dump($getOrder);
        return view('admin.order.index',compact('pathInfo','getOrder','whereData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bankNoName = $this->bank->GetBankAccountNoName();
        //dump($bankNoName);
        return view('admin.order.create',compact('bankNoName'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $json = json_encode([
            'bankCode' => $request->get('bankCode'),
            'bankAccountNo' => $request->get('bankAccountNo'),
            'bankAccountName' => $request->get('bankAccountName'),
        ],JSON_UNESCAPED_UNICODE);

        $data = [
            'merOrderNo' => $this->order->GenerateNo(),
            'amount' => $request->get('amount'),
            'bank_info' => $json, 
            'remarks' => $request->get('remarks'),
            'operator' => \Auth::guard('admin')->user()->mg_id,
            'order_status' => Order::XIAFA_SUBMIT[0]
        ];
        \DB::beginTransaction();
        try {
            $order = $this->order->SaveOrder($data);    //  下发订单
            $this->recheck->CommonSave(['order_id'=>$order->order_id]); //  复审订单
            \DB::commit();
            return JsonResphonse::ResphonseSuccess();
        } catch (\Exception $e) {
            \DB::rollBack();
            return JsonResphonse::JsonData(ApiErrDesc::ORDER_SAVE_FAIL[0],ApiErrDesc::ORDER_SAVE_FAIL[1]);
        }
    }

    /**
     * 查看下发订单详情
     */
    public function check($id)
    {
        $order = $this->order->CommonFirst($id);
        $getManagerIdName = $this->manager->GetManagerIdName();
        
        $order->operator = $getManagerIdName[$order->operator];
        $json =  json_decode($order->bank_info);
        $order->bank_info = '银行编号: ' . $json->bankCode . ' 银行卡账号: ' . $json->bankAccountNo . ' 持卡姓名: ' . $json->bankAccountName;
      
        //dump($order);
        return view('admin.order.check',compact('order'));
    }

    /**
     * 审核订单详情
     */
    public function recheck($order_id)
    {
        $order = $this->order->CommonFirst($order_id);
        $recheck = $this->recheck->Recheck($order_id);
        $getManagerIdName = $this->manager->GetManagerIdName();
        $order->operator = $getManagerIdName[$order->operator];
        $json =  json_decode($order->bank_info);
        $order->bank_info = '银行编号: ' . $json->bankCode . ' 银行卡账号: ' . $json->bankAccountNo . ' 持卡姓名: ' . $json->bankAccountName;

        return view('admin.order.recheck',compact('recheck','order'));
    }
   
}
