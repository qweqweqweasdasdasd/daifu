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
                                <input class="layui-input" placeholder="开始日" name="start" id="start" value="{{$whereData['start']}}" ></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <input class="layui-input" placeholder="截止日" name="end" id="end" value="{{$whereData['end']}}"></div>
                            
                            <div class="layui-input-inline layui-show-xs-block">
                                <select name="mer_status">
                                    <option value="">商户状态</option>
                                    <option value="1" @if(1 == $whereData['mer_status']) selected @endif>启用</option>
                                    <option value="2" @if(2 == $whereData['mer_status']) selected @endif>停用</option>
                                </select>
                            </div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <input type="text" name="key" placeholder="请输入商户号" autocomplete="off" class="layui-input" value="{{$whereData['key']}}" style="width:250px;"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <button class="layui-btn" lay-submit="" lay-filter="sreach" >
                                    <i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-header">  
                        <button class="layui-btn" onclick="xadmin.open('添加商户','/admin/merchant/create',800,600,1)">
                            <i class="layui-icon"></i>添加商户</button>
                        <!-- <button type="button" class="layui-btn layui-btn-normal" title="点击获取最新当前余额" onclick="NewAmount()">点击获取最新当前余额: <span id="amount"></span> 元</button> -->
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>商户名称</th>
                                    <th>商户ID</th>
                                    <th>SIGN (md5加密)</th>
                                    <th>公私钥</th>
                                    <th>商户状态</th>
                                    <th>备注</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($merchants as $v)
                                <tr>
                                    <td>{{$v->mer_id}}</td>
                                    <td>{{$v->mer_name}}</td>
                                    <td>{{$v->merchant_id}}</td>
                                    <td>{{$v->sign}}</td>
                                    <td>
                                    <a href="#" onclick="xadmin.open('配置公私钥','/admin/merchant/deploy/{{$v->mer_id}}',850,500)" class="layui-btn layui-btn-warm">配置公私钥</a>
                                    </td>
                                    <td>{!! common_switch_status($v->mer_status) !!}</td>
                                    <td>{{$v->desc}}</td>
                                    <td>
                                        <a title="编辑"  onclick="xadmin.open('编辑','/admin/merchant/{{$v->mer_id}}/edit',600,800,1)" href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <a title="删除" onclick="mer_del(this,'{{$v->mer_id}}')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                    <div class="page">
                        {{ $merchants->appends(['start' => $whereData['start'],'end' => $whereData['end'],'key' => $whereData['key'],'mer_status' => $whereData['mer_status']])->links() }}
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

        // 执行一个laydate实例
        laydate.render({
            elem:'#start',
            type: 'datetime'
        });

        laydate.render({
            elem:'#end',
            type: 'datetime'
        });

        
    })

    // 删除管理员
    function mer_del(obj,id)
    {
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            $.ajax({
                url:'/admin/merchant/'+id,
                data:'',
                dataType:'json',
                type:'DELETE',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    if(res.code == 1){
                        $(obj).parents('tr').remove();
                        layer.msg('已删除',{icon:1,time:1000});
                    }
                    if(res.code == '4001'){
                        layer.msg(res.msg,{icon:5})
                    }
                }
            })
        });
    }

    // 切换状态
    $('.switch-status').on('click',function(){
        var status = $(this).attr('data-status');
        var id = $(this).parents('tr').find('td:eq(0)').html();
        //debugger;
        // ajax
        $.ajax({
            url:'/admin/merchant/'+id,
            data:{status:status},
            type:'get',
            dataType:'json',
            headers:{
                'X-CSRF-TOKEN':"{{csrf_token()}}"
            },
            success:function(res){
                if(res.code == '1'){
                    window.location.reload(); 
                }
                if(res.code == '10000'){
                    layer.msg(res.msg,{icon:5})
                }
            }
        })
        
    })
</script>

@endsection

