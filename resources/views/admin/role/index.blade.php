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
                                <input type="text" name="r_name"  placeholder="请输入用户名" autocomplete="off" class="layui-input" value="{{$whereData['r_name']}}">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-header">
                        <button class="layui-btn" onclick="xadmin.open('添加角色','/admin/role/create',600,800,1)"><i class="layui-icon"></i>添加</button>
                        
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>   
                                <th>ID</th>
                                <th>角色名</th>
                                <th>拥有权限规则</th>
                                <th>描述</th>
                                <th>状态</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach($getRole as $role)
                            <tr>
                                <td>{{$role->role_id}}</td>
                                <td>{{$role->r_name}}</td>
                                <td>会员列表，问题列表</td>
                                <td>{{$role->remark}}</td>
                                <td class="td-status">
                                    {!! common_switch_status($role->role_status,'role') !!}
                                </td>
                                <td class="td-manage">
                                <a title="分配权限"  onclick="xadmin.open('分配权限','/admin/role/{{$role->role_id}}/assign',600,800,1)" href="javascript:;">
                                    <i class="layui-icon">&#xe631;</i>
                                </a>
                                <a title="编辑"  onclick="xadmin.open('编辑','/admin/role/{{$role->role_id}}/edit',600,800,1)" href="javascript:;">
                                    <i class="layui-icon">&#xe642;</i>
                                </a>
                                <a title="删除" onclick="member_del(this,'{{$role->role_id}}')" href="javascript:;">
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
                            {{ $getRole->appends(['start' => $whereData['start'],'end' => $whereData['end'],'r_name' => $whereData['r_name']])->links() }}
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
        var form = layui.form;
        var laydate = layui.laydate;

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

    // 角色删除
    function member_del(obj,role_id) 
    {
        layer.confirm('确认删除吗?',function(index){
            // ajax
            $.ajax({
                url:'/admin/role/'+role_id,
                data:{role_id:role_id},
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
                    if(res.code == '3001'){
                        layer.msg(res.msg,{icon:5})
                    }
                }
            })
        })
    }

    // 切换状态
    $('.switch-status').on('click',function(){
        var status = $(this).attr('data-status');
        var id = $(this).parents('tr').find('td:eq(0)').html();
        //debugger;
        // ajax
        $.ajax({
            url:'/admin/role/'+id,
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

