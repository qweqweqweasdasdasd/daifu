@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <input type="hidden" name="mer_id" value="{{$merchant->mer_id}}">
                <div class="layui-form-item">
                    <label for="mer_name" class="layui-form-label">
                        <span class="x-red">*</span>商户名称：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="mer_name" name="mer_name" 
                        autocomplete="off" class="layui-input" value="{{$merchant->mer_name}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="merchant_id" class="layui-form-label">
                        <span class="x-red">*</span>商户ID：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="merchant_id" name="merchant_id" autocomplete="off" class="layui-input" value="{{$merchant->merchant_id}}" >
                        
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="sign" class="layui-form-label">
                        <span class="x-red">*</span>SIGN：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="sign" name="sign" 
                        autocomplete="off" class="layui-input"  value="{{$merchant->sign}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="mer_status" class="layui-form-label">
                        <span class="x-red">*</span>商户状态：
                    </label>
                    <div class="layui-input-block">
                        <input type="radio" name="mer_status" value="1" title="启用" @if($merchant->mer_status == 1) checked @endif>
                        <input type="radio" name="mer_status" value="2" title="停用" @if($merchant->mer_status == 2) checked @endif>
                    </div>
                </div>
                <!-- <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>公钥：
                    </label>
                    <div class="layui-input-inline">
                        <textarea name="desc" placeholder="请输入内容" class="layui-textarea" cols="60" rows="30" style="height:300px;"></textarea>
                    </div>
                    <label class="layui-form-label">
                        <span class="x-red">*</span>私钥：
                    </label>
                    <div class="layui-input-inline">
                        <textarea name="desc" placeholder="请输入内容" class="layui-textarea" cols="60" rows="30" style="height:300px;"></textarea>
                    </div>
                </div> -->
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>备注：
                    </label>
                    <div class="layui-input-block">
                    <textarea name="desc" placeholder="请输入备注信息" class="layui-textarea">{{$merchant->desc}}</textarea>
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
        var id = $('input[name="mer_id"]').val();

        form.on('submit(update)',function(data){   
            $.ajax({
                url:'/admin/merchant/'+id,
                data:data.field,
                dataType:'json',
                type:'PATCH',
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

