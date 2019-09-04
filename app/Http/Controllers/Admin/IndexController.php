<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Server\Pay\ExtendPay;
use App\Http\Controllers\Controller;
use App\Repositories\CountRepository;
use App\Repositories\MerchantRepository;

class IndexController extends Controller
{
    /**
     * 角色仓库
     */
    protected $count;

    /**
     * 初始化仓库
     */
    public function __construct(CountRepository $count,MerchantRepository $merchant)
    {
        $this->count = $count;
        $this->merchant = $merchant;
    }

    /**
     * 域名直接跳转到登陆页面
     */
    public function jump()
    {
        return redirect()->route('login');
    }

    /**
     * 后台主页
     */
    public function index()
    {
        return view('admin.index.index');
    }

    /**
     * welcome
     */
    public function welcome()
    {
        $merchants = $this->merchant->GetMerchants();

        $D = [];
        foreach ($merchants as $k => $v) {
            $res = (new ExtendPay($v->mer_id))->ExtendCheckBalance();
            
            if(!$res->code == 200){
                $HengXinBalance = '失败';
            }
            
            $whereData = [
                'merchant_id' => $v->mer_id
            ];
            
            $data = [
                'bank' => $this->count->CountBankNumber(),
                'tijiao' => $this->count->CountOrderNumber($whereData),
                'daozhang' => $this->count->CountDaozhangNumber($whereData),
                'tijiao_amount' => $this->count->CountTJAmountNumber($whereData),
                'xiafa_amount' => $this->count->CountXFAmountNumber($whereData),
                'HengXinBalance' => $res->data->usableAmount,
            ];
            $D[$k] = $data;
        }
        //dump($D);
        return view('admin.index.welcome',compact('D'));
    }
}
