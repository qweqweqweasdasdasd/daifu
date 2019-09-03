@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
            <input type="hidden" name="merid" value="{{$merid}}">
                <div class="layui-form-item layui-form-text">
                    <div class="layui-input-inline">
                        <textarea name="remit_public_key" placeholder="公钥: " class="layui-textarea" cols="50" rows="30" style="height:300px;">{{$merchant->remit_public_key}}</textarea>
                    </div>
                    <label class="layui-form-label">
                    </label>
                    <div class="layui-input-inline">
                        <textarea name="remit_private_key" placeholder="私钥: " class="layui-textarea" cols="50" rows="30" style="height:300px;">{{$merchant->remit_private_key}}</textarea>
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
            $.ajax({
                url:'/admin/merchant/doDeploy',
                data:data.field,
                dataType:'json',
                type:'post',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    if(res.code == '1'){
                        layer.alert(res.msg,{icon:6},function(){
                            xadmin.close();
                            xadmin.father_reload();
                        })
                    }
                    if(res.code == 0){
                        layer.msg(res.msg);
                    }
                    if(res.code == '422'){
                        layer.msg(res.msg);
                    }
                    if(res.code == '9000'){
                        layer.msg(res.msg);
                    }
                }
            })
            
            return false;  
        })

        form.on('select(select)',function(data){
            console.log(data.value);
            var bank_id = data.value;
            $.ajax({
                url:'/admin/bank/getOne/'+bank_id,
                data:'',
                dataType:'json',
                type:'post',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    if(res.code == 1){
                        var json = JSON.parse(res.data);
                        $('input[name="bankCode"]').val(json.bankCode);
                        $('input[name="bankAccountNo"]').val(json.bankAccountNo);
                        $('input[name="bankAccountName"]').val(json.bankAccountName);
                        $('input[name="notifyUrl"]').val(json.notifyUrl);
                    }
                }
            })
        })
    })
</script>
@endsection

