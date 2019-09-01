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
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5">
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input"  autocomplete="off" placeholder="开始日" name="start" id="start" value="{{$whereData['start']}}">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input class="layui-input"  autocomplete="off" placeholder="截止日" name="end" id="end" value="{{$whereData['end']}}">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <input type="text" name="mg_name" value="{{$whereData['mg_name']}}" placeholder="请输入管理员名" autocomplete="off" class="layui-input">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-header">
                        <button class="layui-btn" onclick="xadmin.open('添加管理员','/admin/manager/create',600,800,1)"><i class="layui-icon">&#xe608;</i>添加</button>
                        <button class="layui-btn" onclick="xadmin.open('清除二次验证令牌','/admin/manager/unbound',500,350)"><i class="layui-icon">&#xe64c;</i>清除二次验证令牌</button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                               
                                <th>ID</th>
                                <th>登录名</th>
                                <th>IP</th>
                                <th>邮箱</th>
                                <th>角色</th>
                                <th>加入时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach($getManagers as $manager)
                            <tr>
                               
                                <td>{{$manager->mg_id}}</td>
                                <td>{{$manager->mg_name}}</td>
                                <td>{{$manager->last_login_ip}}</td>
                                <td>{{$manager->mg_email}}</td>
                                <td>
                                    @if($manager->mg_id != 1)
                                    @foreach($manager->roles as $role)
                                    <span class="layui-badge @if($role->role_status == 1) layui-bg-green @else layui-bg-gray @endif"  >{{$role->r_name}}</span>
                                    @endforeach
                                    @else
                                    <span class="layui-badge layui-bg-green">超级管理员</span>
                                    @endif
                                </td>
                                <td>{{$manager->created_at}}</td>
                                <td class="td-status">
                                    @if($manager->mg_id != 1)
                                    {!! common_switch_status($manager->mg_status) !!}
                                    @endif
                                </td>
                                <td class="td-manage">
                        
                                <a title="编辑"  onclick="xadmin.open('编辑','/admin/manager/{{$manager->mg_id}}/edit',600,800,1)" href="javascript:;">
                                    <i class="layui-icon">&#xe642;</i>
                                </a>
                                @if($manager->mg_id != 1)
                                <a title="删除" onclick="manager_del(this,'{{$manager->mg_id}}')" href="javascript:;">
                                    <i class="layui-icon">&#xe640;</i>
                                </a>
                                @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                        <div class="page">
                        {{ $getManagers->appends(['start' => $whereData['start'],'end' => $whereData['end'],'mg_name' => $whereData['mg_name']])->links() }}
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
    function manager_del(obj,id)
    {
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            $.ajax({
                url:'/admin/manager/'+id,
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
            url:'/admin/manager/'+id,
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

