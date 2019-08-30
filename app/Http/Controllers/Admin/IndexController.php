<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
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
        $data = [
            'bank_count' => '',
            'tijiao_count' => '',
            'daozhang_count' => '',
            'tijiao_amount' => '',
            'xiafa_amount' => '',
            'hengxin_count' => '',
        ];
        
        return view('admin.index.welcome');
    }
}
