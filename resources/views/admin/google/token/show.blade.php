@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
        <div style="padding: 20px; background-color: #F2F2F2;">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md6">
            <div class="layui-card">
                <div class="layui-card-header">操作面板</div>
                    <div class="layui-card-body">
                    <form class="layui-form">
                        <div class="layui-form-item">
                            <label for="username" class="layui-form-label">
                            选择管理员</label>
                            <div class="layui-input-block">
                                <select name="mg_id">
                                    <!-- <option>选择管理员</option> -->
                                    @foreach($managers as $v)
                                        <option value="{{$v->mg_id}}">{{$v->mg_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label for="L_repass" class="layui-form-label"></label>
                            <button class="layui-btn" lay-filter="update" lay-submit="">生成谷歌二维码</button>
                        </div>
                        <div class="layui-form-item" id="content">
                            <label  class="layui-form-label"></label>
                            <img src="" alt="" id="QRCode" >
                        </div>
                    </form>
                </div>
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
    layui.use(['form','layer'],function(){
        var form = layui.form;
        var layer = layui.layer;

        form.on('submit(update)',function(data){
            console.log(data);
            // ajax
            $.ajax({
                url:'/secret/GoogleToken',
                data:data.field,
                dataType:'json',
                type:'post',
                headers:{
                    'X-CSRF-TOKEN':'{{csrf_token()}}'
                },
                success:function(res){
                    if(res.code == '1'){
                        //debugger;
                        var QRCode = res.data.QRCode;
                        $('#QRCode').attr('src',QRCode);
                    }
                    if(res.code == ''){
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

