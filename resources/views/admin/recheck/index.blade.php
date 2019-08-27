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
                                <input class="layui-input" placeholder="截止日" name="end" id="end" value="{{$whereData['start']}}"></div>
                            
                            <div class="layui-input-inline layui-show-xs-block">
                                <select name="recheck_status">
                                    <option value="">审核状态</option>
                                    <option value="1" @if($whereData['recheck_status'] == 1) selected @endif>已审核</option>
                                    <option value="2" @if($whereData['recheck_status'] == 2) selected @endif>未审核</option>
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
                    <div class="layui-card-header">  
                            <button class="layui-btn" onclick="xadmin.open('查看当前金额','/admin/order/create',800,600,1)">
                            查看当前金额</button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>订单编号(点击查看详情)</th>
                                    <th>下发金额</th>
                                    <!-- <th>操作者</th> -->
                                    <!-- <th>下发卡详细</th> -->
                                    <!-- <th>审核详情</th> -->
                                    <th>下发备注</th>
                                    <th>审核备注</th>
                                    <!-- <th>提交时间</th> -->
                                    <th>下发状态</th>
                                    <th>审核状态</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recheck as $v)
                                <tr>
                                    <td>{{$v->recheck_id}}</td>
                                    <td>{{$v->merOrderNo}} <a href="#" onclick="xadmin.open('查看订单详情','/admin/order/check/{{$v->order_id}}',700,500)" >&nbsp;&nbsp;<i class="layui-icon">&#xe615;</i></a></td>
                                    <!-- <td>{{$v->amount}}</td> -->
                                    <!-- <td>{{$v->operator}}</td> -->
                                    <!-- <td>{{$v->bank_info}}</td> -->
                                    <td>{{$v->amount}}</td>
                                    <td>{{$v->remarks}}</td>
                                    <td>{{$v->desc}}</td>
                                    <!-- <td>{{$v->created_at}}</td> -->
                                    <td>{!! xiafa_order_show_status($v->order_status) !!}</td>
                                    <td>{!! shenhe_recheck_show_status($v->recheck_status) !!}</td>
                                    <td class="td-manage">
                                        <a title="确认下发" title="确认下发" onclick="xadmin.open('确认下发','/admin/recheck/{{$v->order_id}}',700,500)" href="javascript:;">
                                            <i class="layui-icon">&#xe609;</i></a>
                                        <!-- <a title="删除" onclick="member_del(this,'要删除的id')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i></a> -->
                                    </td>
                                </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@endsection

@section('my-js')
<script>
    layui.config({
        base :'./../x-admin/lib/layui/lay/modules/'
    })
    layui.use(['laydate','form','notice'],function(){
        var laydate = layui.laydate;
        var form = layui.form;
        var notice = layui.notice;

        // 初始化配置，同一样式只需要配置一次，非必须初始化，有默认配置
        notice.options = {
            closeButton:true,//显示关闭按钮
            debug:false,//启用debug
            positionClass:"toast-top-right",//弹出的位置,
            showDuration:"300",//显示的时间
            hideDuration:"1000",//消失的时间
            timeOut:"2000",//停留的时间
            extendedTimeOut:"1000",//控制时间
            showEasing:"swing",//显示时的动画缓冲方式
            hideEasing:"linear",//消失时的动画缓冲方式
            iconClass: 'toast-info', // 自定义图标，有内置，如不需要则传空 支持layui内置图标/自定义iconfont类名
            onclick: null, // 点击关闭回调
        };

        // 执行一个laydate实例
        laydate.render({
            elem:'#start',
            type: 'datetime'
        });

        laydate.render({
            elem:'#end',
            type: 'datetime'
        });

        setInterval(function(){
            // ajax
            $.ajax({
                url:'/admin/recheck/notice',
                data:'',
                datatype:'json',
                type:'post',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    var json = JSON.parse(res);
                    if(json.code == 1){
                        console.log(json.data.count);
                        notice.warning('审核还有未处理 '+json.data.count+' 单');
                    }
                    if(json.code == 0){
                        console.log(json.msg);
                    }
                }
            })
        },8000);
    })

</script>
@endsection

