@extends('admin/common/layout')

@section('content')

<body>
    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="#">{{$pathInfo['module']}}</a>
            <a href="#">{{$pathInfo['cu']}}</a>
            <a>
                <cite>{{$pathInfo['method']}}</cite></a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
        </a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5">
                            <div class="layui-input-inline layui-show-xs-block">
                                <input class="layui-input" placeholder="开始日" name="start" id="start" value="{{$whereData['start']}}"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <input class="layui-input" placeholder="截止日" name="end" id="end" value="{{$whereData['end']}}"></div>
                            
                            <div class="layui-input-inline layui-show-xs-block">
                                <select name="order_status">
                                    <option value="">下发状态</option>
                                    @foreach(config('order.status') as $k=>$v)
                                        <option value="{{$k}}" @if($k == $whereData['order_status']) selected @endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <input type="text" name="merOrderNo" placeholder="请输入订单号" autocomplete="off" class="layui-input" value="{{$whereData['merOrderNo']}}" style="width:250px;"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <button class="layui-btn" lay-submit="" lay-filter="sreach" >
                                    <i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-body">  
                        
                        <div class="layui-form layui-input-inline layui-show-xs-block">
                            <!--  -->
                            <select name="order_status"  lay-filter="order_status" id="sele">
                                <option value="">选择商户</option>
                                @foreach($merchants as $k=>$v)
                                    <option value="{{$v->mer_id}}" >{{$v->mer_name}}</option>
                                @endforeach
                            </select>
                        </div>
                            <span class="layui-badge layui-bg-orange">余额: <span  id="amount"></span></span>
                        </div>
                    </div>
                    <div class="layui-card-header">  
                        <button class="layui-btn" onclick="xadmin.open('下发操作','/admin/order/create',800,600,1)">
                            <i class="layui-icon"></i>下发操作</button>
                        <!-- <button type="button" class="layui-btn layui-btn-normal" title="点击获取最新当前余额" onclick="NewAmount()">点击获取最新当前余额: <span id="amount"></span> 元</button> -->
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>订单编号(查看订单信息)</th>
                                    <!-- <th>下发金额</th> -->
                                    <!-- <th>操作者</th> -->
                                    <!-- <th>下发卡详细</th> -->
                                    <th>审核详情</th>
                                    <th>下发备注</th>
                                    <th>审核备注</th>
                                    <!-- <th>提交时间</th> -->
                                    <th>下发状态</th>
                                    <th>审核状态</th>
                                    <!-- <th>操作</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($getOrder as $v)
                                <tr>
                                    <td>{{$v->order_id}}</td>
                                    <td>{{$v->merOrderNo}} <a href="#" onclick="xadmin.open('查看订单信息','/admin/order/check/{{$v->order_id}}',700,500)" >&nbsp;&nbsp;<i class="layui-icon">&#xe615;</i></a></td>
                                    <!-- <td>{{$v->amount}}</td> -->
                                    <!-- <td>{{$v->operator}}</td> -->
                                    <!-- <td>{{$v->bank_info}}</td> -->
                                    <td><a href="#" onclick="xadmin.open('查看订单详情','/admin/order/recheck/{{$v->order_id}}',700,500)" class="layui-btn layui-btn-warm">审核查看</a></td>
                                    <td>{{$v->remarks}}</td>
                                    <td>{!! interface_return_bank_info($v->desc) !!}</td>
                                    <!-- <td>{{$v->created_at}}</td> -->
                                    <td>{!! xiafa_order_show_status($v->order_status) !!}</td>
                                    <td>{!! shenhe_recheck_show_status($v->recheck_status) !!}</td>
                                    <!-- <td class="td-manage">
                                        <a title="查看" onclick="xadmin.open('编辑','order-view.html')" href="javascript:;">
                                            <i class="layui-icon">&#xe63c;</i></a>
                                        <a title="删除" onclick="member_del(this,'要删除的id')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i></a>
                                    </td> -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                        <div class="page">
                        {{ $getOrder->appends(['start' => $whereData['start'],'end' => $whereData['end'],'merOrderNo' => $whereData['merOrderNo'],'order_status' => $whereData['order_status']])->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@endsection

@section('my-js')
<script>
    layui.use(['laydate','form'],function(){
        var laydate = layui.laydate;
        var form = layui.form;
        var $ = layui.jquery;

        // 执行一个laydate实例
        laydate.render({
            elem:'#start',
            type: 'datetime'
        });

        laydate.render({
            elem:'#end',
            type: 'datetime'
        });

        $('#amount').html('请选择商户查看余额! ');
        form.on('select(order_status)', function(data){
            var mer_id = data.value;
            // ajax
            _ajax(mer_id);
            console.log(data);
        });
        
        // 定时器
        setInterval(function(){
            var mer_id = $('#sele').find("option:selected").val();
            if(mer_id == ''){
                return false;
            }
            console.log(mer_id);
            _ajax(mer_id)
        },5000);

        // 请求接口
        function _ajax(mer_id){
            $.ajax({
                url:'/api/balance/query/'+mer_id,
                data:'',
                type:'post',
                dataType:'json',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    if(res.code == 1){
                        $('#amount').html(res.data+' 元');
                    }
                    if(res.code == 0){
                        layer.msg(res.msg,function(){
                            $('#amount').html(res.msg);
                        });
                    }

                }
            })
        }
    })

    
</script>
<script src="/x-admin/js/balance.select.query.js"></script>
@endsection

