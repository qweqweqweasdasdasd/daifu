@extends('admin/common/layout')

@section('content')

<div class="layui-fluid">
    <div class="layui-row">
        <form action="" method="post" class="layui-form layui-form-pane">
            <input type="hidden" name="role_id" value="{{$role->role_id}}">
            <div class="layui-form-item">
                <label for="r_name" class="layui-form-label">
                    <span class="x-red">*</span>角色名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="r_name" name="r_name"  value="{{$role->r_name}}"
                    autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">
                    拥有权限
                </label>
                <table  class="layui-table layui-input-block">
                    <tbody>
                        @foreach($rules as $v)
                        <tr>
                            <td>
                                <input type="checkbox" name="rule_id[]" lay-skin="primary" lay-filter="father" title="{{$v['rule_name']}}" value="{{$v['rule_id']}}"
                                @if(in_array($v['rule_id'],$has_rules))
                                    checked
                                @endif
                                >
                            </td>
                            <td>
                                <div class="layui-input-block">
                                    @if(count($v['son']))
                                        @foreach($v['son'] as $vv)
                                        <input name="rule_id[]" lay-skin="primary" type="checkbox" title="{{$vv['rule_name']}}" value="{{$vv['rule_id']}}"
                                            @if(in_array($vv['rule_id'],$has_rules))
                                                checked
                                            @endif
                                        > 
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="layui-form-item layui-form-text">
                <label for="remark" class="layui-form-label">
                    描述
                </label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="remark" name="remark" class="layui-textarea">{{$role->remark}}</textarea>
                </div>
            </div>
            <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="add">增加</button>
            </div>
        </form>
    </div>
</div>

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

         //监听提交
        form.on('submit(add)', function(data){
            //发异步，把数据提交给php
            var id = $('input[name="role_id"]').val();

            $.ajax({
                url:'/admin/role/'+ id +'/assign',
                data:data.field,
                type:'post',
                dataType:'json',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    if(res.code == 1){
                        layer.alert("增加成功", {icon: 6},function () {
                            // 获得frame索引
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                        });
                    }
                }
            })
            
            return false;
        });

        form.on('checkbox(father)', function(data){

        if(data.elem.checked){
            $(data.elem).parent().siblings('td').find('input').prop("checked", true);
            form.render(); 
        }else{
        $(data.elem).parent().siblings('td').find('input').prop("checked", false);
            form.render();  
        }
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


</script>
@endsection

