@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <blockquote class="layui-elem-quote">欢迎管理员：
                            <span class="x-red">{{Auth::guard('admin')->user()->mg_name}}</span>！最后登录时间:{{Auth::guard('admin')->user()->last_login_time}}
                        </blockquote>
                    </div>
                </div>
            </div>   
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">数据统计</div>
                    @foreach($D as $data)
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <h3>{{$data['bank']['name']}}</h3>
                                    <p>
                                        <cite>{{$data['bank']['count']}} 张</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" onclick="location.reload()" class="x-admin-backlog-body">
                                    <h3>{{$data['tijiao']['name']}}</h3>
                                    <p>
                                        <cite>{{$data['tijiao']['count']}} 笔</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" onclick="location.reload()" class="x-admin-backlog-body">
                                    <h3>{{$data['daozhang']['name']}}</h3>
                                    <p>
                                        <cite>{{$data['daozhang']['count']}} 笔</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" onclick="location.reload()" class="x-admin-backlog-body">
                                    <h3>{{$data['tijiao_amount']['name']}}</h3>
                                    <p>
                                        <cite>{{$data['tijiao_amount']['count']}} 元</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" onclick="location.reload()" class="x-admin-backlog-body">
                                    <h3>{{$data['xiafa_amount']['name']}}</h3>
                                    <p>
                                        <cite>{{$data['xiafa_amount']['count']}} 元</cite></p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6 ">
                                <a href="javascript:;" onclick="location.reload()" class="x-admin-backlog-body">
                                    <h3>第三方余额</h3>
                                    <p>
                                        <cite>{{$data['HengXinBalance']}} 元</cite></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>  
            <!-- <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">系统信息</div>
                    <div class="layui-card-body ">
                        <table class="layui-table">
                            <tbody>
                                <tr>
                                    <th>xxx版本</th>
                                    <td>1.0.180420</td></tr>
                                <tr>
                                    <th>服务器地址</th>
                                    <td>x.xuebingsi.com</td></tr>
                                <tr>
                                    <th>操作系统</th>
                                    <td>WINNT</td></tr>
                                <tr>
                                    <th>运行环境</th>
                                    <td>Apache/2.4.23 (Win32) OpenSSL/1.0.2j mod_fcgid/2.3.9</td></tr>
                                <tr>
                                    <th>PHP版本</th>
                                    <td>5.6.27</td></tr>
                                <tr>
                                    <th>PHP运行方式</th>
                                    <td>cgi-fcgi</td></tr>
                                <tr>
                                    <th>MYSQL版本</th>
                                    <td>5.5.53</td></tr>
                                <tr>
                                    <th>ThinkPHP</th>
                                    <td>5.0.18</td></tr>
                                <tr>
                                    <th>上传附件限制</th>
                                    <td>2M</td></tr>
                                <tr>
                                    <th>执行时间限制</th>
                                    <td>30s</td></tr>
                                <tr>
                                    <th>剩余空间</th>
                                    <td>86015.2M</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>      -->
            <style id="welcome_style"></style>
            
        </div>
    </div>
    </div>
</body>

@endsection

