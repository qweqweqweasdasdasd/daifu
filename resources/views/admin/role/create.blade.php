@extends('admin/common/layout')

@section('content')

<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form action="" method="post" class="layui-form layui-form-pane">
            <div class="layui-form-item">
                <label for="r_name" class="layui-form-label">
                    <span class="x-red">*</span>角色名
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="r_name" name="r_name" required="" lay-verify="required"
                    autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>状态
                </label>
                <div class="layui-input-inline">
                    <input type="radio" name="role_status" value="1" title="启用" checked="">
                    <input type="radio" name="role_status" value="2" title="停用"> 
                </div>
            </div>
            <!-- <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">
                    拥有权限
                </label>
                <table  class="layui-table layui-input-block">
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" name="like1[write]" lay-skin="primary" lay-filter="father" title="用户管理">
                            </td>
                            <td>
                                <div class="layui-input-block">
                                    <input name="id[]" lay-skin="primary" type="checkbox" title="用户停用" value="2"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="用户删除"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="用户修改"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="用户改密"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="用户列表">
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="用户改密"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="用户列表">
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="用户改密"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="用户列表"> 
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                
                                <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="文章管理" lay-filter="father">
                            </td>
                            <td>
                                <div class="layui-input-block">
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="文章添加"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="文章删除"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="文章修改"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="文章改密"> 
                                    <input name="id[]" lay-skin="primary" type="checkbox" value="2" title="文章列表"> 
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->
            <div class="layui-form-item layui-form-text">
                <label for="remark" class="layui-form-label">
                    描述
                </label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" id="remark" name="remark" class="layui-textarea"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
            <button class="layui-btn" lay-submit="" lay-filter="add">增加</button>
            </div>
        </form>
    </div>
</div>
</body>

@endsection

@section('my-js')
<script>
    layui.use(['form','layer'],function(){
        var $ = layui.jquery;
        var form = layui.form;
        var layer = layui.layer;

        // 监听提交
        form.on('submit(add)',function(data){
            console.log(data.field);
            //ajax
            $.ajax({
                url:'/admin/role',
                data:data.field,
                dataType:'json',
                type:'post',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    if(res.code == '1'){
                        layer.alert(res.msg,{icon:6},function(){
                            window.parent.location.reload();        
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        })
                    }
                    if(res.code == '3000'){
                        layer.msg(res.msg,{icon:5})
                    }
                    if(res.code == '422'){
                        layer.msg(res.msg);
                    }

                }
            })
            return false;
        })

        // 
    })
</script>
@endsection

