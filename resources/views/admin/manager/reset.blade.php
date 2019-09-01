@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
            <div class="layui-form-item">
                    <label for="old_password" class="layui-form-label">
                        旧密码
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="old_password" name="old_password" 
                        autocomplete="off" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="new_password" class="layui-form-label">
                        新密码
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="password" name="password" 
                        autocomplete="off" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="gooleToken" class="layui-form-label">
                        确认密码
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="password_confirmation" name="password_confirmation" 
                        autocomplete="off" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="update" lay-submit="">
                        更新
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

@endsection

@section('my-js')
<script>
    layui.use(['form','layer'],function(){
        var form = layui.form;
        var layer = layui.layer;

        form.on('submit(update)',function(data){
            console.log(data);
            // ajax
            $.ajax({
                url:'/admin/manager/reset',
                data:data.field,
                dataType:'json',
                type:'post',
                headers:{
                    'X-CSRF-TOKEN':'{{csrf_token()}}'
                },
                success:function(res){
                    if(res.code == '1'){
                        layer.alert(res.msg,{icon:6},function(){
                            xadmin.close();
                            xadmin.father_reload();
                        })
                    }
                    if(res.code == '4005'){
                        layer.msg(res.msg);
                    }
                    if(res.code == '4006'){
                        layer.msg(res.msg);
                    }
                    if(res.code == '4007'){
                        layer.msg(res.msg);
                    }
                    if(res.code == ''){
                        layer.msg(res.msg);
                    }
                    if(res.code == '422'){
                        layer.msg(res.msg);
                    }
                    
                }
            })
            return false;
        })
    })
</script>
@endsection

