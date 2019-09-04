@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <input type="hidden" name="merchant_id" value="{{$order->merchant_id}}">
                <input type="hidden" name="order_id" value="{{$order->order_id}}" readonly="readonly">
                <div class="layui-form-item">
                    <label for="merOrderNo" class="layui-form-label">
                    商户订单号：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="merOrderNo" name="merOrderNo" 
                        autocomplete="off" class="layui-input" value="{{$order->merOrderNo}}" readonly="readonly">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="amount" class="layui-form-label">
                    金额：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="amount" name="amount"
                        autocomplete="off" class="layui-input" value="{{$order->amount}}" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="bankCode" class="layui-form-label">
                    银行编码：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="bankCode" name="bankCode"
                        autocomplete="off" class="layui-input" value="{{$order->bankCode}}" readonly="readonly">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="bankAccountNo" class="layui-form-label">
                    银行账号：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="bankAccountNo" name="bankAccountNo"
                        autocomplete="off" class="layui-input" value="{{$order->bankAccountNo}}" >
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="bankAccountName" class="layui-form-label">
                    持卡人名字：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="bankAccountName" name="bankAccountName"
                        autocomplete="off" class="layui-input" value="{{$order->bankAccountName}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="gooleToken" class="layui-form-label">
                    二次验证：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="gooleToken" name="gooleToken"
                        autocomplete="off" class="layui-input" placeholder="只有超级管理员二次验证可以通过">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="remarks" class="layui-form-label">
                    下发备注：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="remarks" name="remarks"
                        autocomplete="off" class="layui-input" value="{{$order->remarks}}" readonly="readonly">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="update" lay-submit="">
                        请求接口
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
            var id = $('input[name="order_id"]').val();

            // ajax
            $.ajax({
                url:'/admin/recheck/'+id,
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
                    if(res.code == '7001'){
                        layer.msg(res.msg,{icon:5})
                    }
                    if(res.code == '2003'){
                        layer.msg(res.msg,{icon:5})
                    }
                    if(res.code == '2002'){
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

