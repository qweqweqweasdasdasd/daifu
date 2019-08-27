@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <input type="hidden" name="mg_id" value="{{$manager->mg_id}}">
                <div class="layui-form-item">
                    <label for="mg_name" class="layui-form-label">
                        <span class="x-red">*</span>管理员名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="mg_name" name="mg_name" 
                        autocomplete="off" class="layui-input" value="{{$manager->mg_name}}">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>将会成为您唯一的登入名
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="mg_email" class="layui-form-label">
                        <span class="x-red">*</span>邮箱
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="mg_email" name="mg_email"
                        autocomplete="off" class="layui-input" value="{{$manager->mg_email}}">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>状态</label>
                    <div class="layui-input-block">
                    <input type="radio" name="mg_status" value="1" title="启用" @if($manager->mg_status == 1) checked @endif>
                    <input type="radio" name="mg_status" value="2" title="停用" @if($manager->mg_status == 2) checked @endif>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>角色</label>
                    <div class="layui-input-block">
                        @foreach($roleNameIdStatus as $v)
                            <input type="checkbox" name="role_ids[]" lay-skin="primary" title="{{$v->r_name}}" 
                            @if($v->role_status == 2) disabled @endif 
                            @if(in_array($v->role_id,$manager->role_ids_arr) )
                                checked
                            @endif
                            value="{{$v->role_id}}"> 
                        @endforeach
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
            var mg_id = $('input[name="mg_id"]').val();
            //debugger;
            // ajax
            $.ajax({
                url:'/admin/manager/'+mg_id,
                data:data.field,
                dataType:'json',
                type:'PATCH',
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
                    if(res.code == '4002'){
                        layer.msg(res.msg,{icon:5})
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

