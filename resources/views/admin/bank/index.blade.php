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
                                <input type="text" name="bankAccountNo" value="{{$whereData['bankAccountNo']}}" placeholder="请输入银行账号" autocomplete="off" class="layui-input" style="width:250px;">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-header">
                        <button class="layui-btn" onclick="xadmin.open('添加银行','/admin/bank/create',600,800,1)"><i class="layui-icon"></i>添加</button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>银行编码</th>
                                <th>银行账号</th>
                                <th>持卡人名字</th>
                                <th>加入时间</th>
                                <th>状态</th>
                                <th>备注</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                                @foreach($getBank as $v)
                                <tr>
                                    <td>{{$v->bank_id}}</td>
                                    <td>{{$v->bankCode}}</td>
                                    <td>{{$v->bankAccountNo}}</td>
                                    <td>{{$v->bankAccountName}}</td>
                                    <td>{{$v->created_at}}</td>
                                    <td>{!! common_switch_status($v->bank_status) !!}</td>
                                    <td>{{$v->remarks}}</td>
                                    <td class="td-manage">
                                        <a title="编辑"  onclick="xadmin.open('编辑','/admin/bank/{{$v->bank_id}}/edit',600,800,1)" href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <a title="删除" onclick="bank_del(this,'{{$v->bank_id}}')" href="javascript:;">
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
                        {{ $getBank->appends(['start' => $whereData['start'],'end' => $whereData['end'],'bankAccountNo' => $whereData['bankAccountNo']])->links() }}
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
    function bank_del(obj,id)
    {
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            $.ajax({
                url:'/admin/bank/'+id,
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
                    if(res.code == '6002'){
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
            url:'/admin/bank/'+id,
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

