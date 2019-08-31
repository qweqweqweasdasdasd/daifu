@extends('admin/common/layout')

@section('my-css')
<link rel="stylesheet" href="{{asset('/x-admin/css/login.css')}}">
@endsection
@section('content')
<div class="login layui-anim layui-anim-up">
    <div class="message">{{env('APP_NAME')}}</div>
    <div id="darkbannerwrap"></div>
    
    <form method="post" class="layui-form" >
        <input name="mg_name" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
        <hr class="hr15">
        <input name="gooleToken" lay-verify="required" placeholder="谷歌二次验证"  type="text" class="layui-input">
        <hr class="hr15">
        <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20" >
    </form>
</div>
@endsection
@section('my-js')
<script>
    $(function(){
        layui.use('form',function(){
            var form = layui.form;

            form.on('submit(login)',function(data){
                
                $.ajax({
                    url:'/admin/login',
                    data:data.field,
                    dataType:'json',
                    type:'post',
                    headers:{
                        'X-CSRF-TOKEN':"{{csrf_token()}}"
                    },
                    success:function(res){
                        if(res.code == 1){
                            location.href = res.data.href
                        }
                        
                        if(res.code == '2001'){
                            layer.msg(res.msg)
                        }
                        if(res.code == '2000'){
                            layer.msg(res.msg)
                        }
                        if(res.code == '2002'){
                            layer.msg(res.msg)
                        }
                        if(res.code == '2003'){
                            layer.msg(res.msg)
                        }
                        if(res.code == '422'){
                            layer.msg(res.msg)
                        }
                    }
                })
                return false;
            })
        })
    })
</script>
@endsection

