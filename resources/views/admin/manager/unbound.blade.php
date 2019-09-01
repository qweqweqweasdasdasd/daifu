@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label  class="layui-form-label"> 
                        管理员列表
                    </label>
                    <div class="layui-input-block">
                        @foreach($GetManagerIdName as $k=>$v)
                            <input type="checkbox" name="mg_id[]" title="{{$v}}" lay-skin="primary" value="{{$k}}">
                        @endforeach
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="gooleToken" class="layui-form-label">
                        二次验证
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="gooleToken" name="gooleToken" 
                        autocomplete="off" class="layui-input" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="update" lay-submit="">
                        确认清除二次验证
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
                url:'/admin/manager/unbound',
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
                    if(res.code == '4003'){
                        layer.msg(res.msg,{icon:5})
                    }
                    if(res.code == '4004'){
                        layer.msg(res.msg,{icon:5})
                    }
                    if(res.code == '2002'){
                        layer.msg(res.msg,{icon:5})
                    }
                    if(res.code == '2003'){
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

