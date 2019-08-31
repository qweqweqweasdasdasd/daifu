<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Server\Pay\HengxinPay;
use App\Http\Controllers\Controller;
use App\Repositories\CountRepository;

class IndexController extends Controller
{
    /**
     * 角色仓库
     */
    protected $count;

    /**
     * 初始化仓库
     */
    public function __construct(CountRepository $count)
    {
        $this->count = $count;
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
        $res = (new HengxinPay())->CheckBalance();
        if(!$res->code == 200){
            $HengXinBalance = '失败';
        }

        $data = [
            'bank' => $this->count->CountBankNumber(),
            'tijiao' => $this->count->CountOrderNumber(),
            'daozhang' => $this->count->CountDaozhangNumber(),
            'tijiao_amount' => $this->count->CountTJAmountNumber(),
            'xiafa_amount' => $this->count->CountXFAmountNumber(),
            'HengXinBalance' => $res->data->usableAmount,
        ];
        //dump($data);
        return view('admin.index.welcome',compact('data'));
    }
}
