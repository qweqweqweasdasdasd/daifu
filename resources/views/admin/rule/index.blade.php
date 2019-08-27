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
                        <!-- <form class="layui-form layui-col-space5">
                            
                            <div class="layui-inline layui-show-xs-block">
                                <select name="contrller">
                                    <option>请控制器</option>
                                    @foreach($getRuleC as $c)
                                    <option value="{{$c->rule_c}}" @if($whereData['contrller'] == $c->rule_c) selected @endif>{{$c->rule_c}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <select name="action">
                                    <option>请方法</option>
                                    @foreach($getRuleA as $a)
                                    <option value="{{$a->rule_a}}" @if($whereData['action'] == $a->rule_a) selected @endif>{{$a->rule_a}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form> -->
                    </div>
                    <div class="layui-card-header">
                        <button class="layui-btn" onclick="xadmin.open('添加权限','/admin/rule/create',600,800,1)"><i class="layui-icon"></i>添加</button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>权限名称</th>
                                <th>权限规则</th>
                                <th>控制器</th>
                                <th>方法</th>
                                <th>是否显示</th>
                                <th>是否验证</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach($getRule as $v)
                            <tr>
                                <td>{{$v['rule_id']}}</td>
                                <td>{{$v['rule_name_text']}}</td>
                                <td>{{$v['route']}}</td>
                                <td>{{$v['rule_c']}}</td>
                                <td>{{$v['rule_a']}}</td>
                                <td class="td-status">
                                    <input type="checkbox" @if($v['is_show'] == 1) checked @endif name="open" lay-skin="switch" lay-filter="switch" title="开关" data="is_show" >
                                </td>
                                <td class="td-status">
                                    <input type="checkbox" @if($v['is_verify'] == 1) checked @endif name="open" lay-skin="switch" lay-filter="switch" title="开关" data="is_verify" >
                                </td>
                                <td class="td-manage">
                                <a title="编辑权限"  onclick="xadmin.open('编辑权限','/admin/rule/{{$v["rule_id"]}}/edit',600,800,1)" href="javascript:;">
                                    <i class="layui-icon">&#xe642;</i>
                                </a>
                                <a title="删除" onclick="rule_del(this,'{{$v["rule_id"]}}')" href="javascript:;">
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
    layui.use(['form','jquery'],function(){
        var form = layui.form;
        var $ = layui.jquery;
        //监听指定开关
        // form.on('switch(switchTest)', function(data){
        //     layer.msg('开关checked：'+ (this.checked ? 'true' : 'false'), {
        //     offset: '6px'
        //     });
        //     layer.tips('温馨提示：请注意开关状态的文字可以随意定义，而不仅仅是ON|OFF', data.othis)
        // });

        // 监听指定是否显示开关
        form.on('switch(switch)',function(data){
            var param = $(this).attr('data');
            var d = this.checked ? '1' : '2';
            var id = $(this).parents('tr').find('td:eq(0)').html();
            $.ajax({
                url:'/admin/rule/switch/' + param,
                data:{d:d,id:id},
                dataType:'json',
                type:'post',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    if(res.code == 1){
                        //debugger
                        if(res.data == 'is_verify'){
                            layer.msg('状态:'+ (this.checked ? ' 验证' : ' 不验证'),{
                                offset :'6px'
                            }); 
                        }
                        if(res.data == 'is_show'){
                            layer.msg('状态:'+ (this.checked ? ' 显示' : ' 不显示'),{
                                offset :'6px'
                            });
                        }
                    }
                    if(res.code == '5003'){
                        layer.msg(res.msg,{icon:5})
                    }
                }
            })
           
        })

        // 监听指定是否验证开关
        // form.on('switch(switchVerify)',function(data){
        //     layer.msg('状态:'+ (this.checked ? ' 验证' : ' 不验证'),{
        //         offset :'6px'
        //     });
        // })
    
    })

    function rule_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            $.ajax({
                url:'/admin/rule/'+id,
                data:'',
                dataType:'json',
                type:'DELETE',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    if(res.code == 1){
                        $(obj).parents("tr").remove();
                        layer.msg('已删除!',{icon:1,time:1000});
                    }
                    if(res.code == '5001'){
                        layer.msg(res.msg,{icon:5})
                    }
                }
            })
        });
    }

</script>
@endsection

