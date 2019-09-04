@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="amount" class="layui-form-label">
                        <span class="x-red">*</span>金额：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="amount" name="amount" 
                        autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>金额最低2元, 最高以平哥那边金额为准.
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>从商户下发：
                    </label>
                    <div class="layui-input-block">
                        <select id="" name="merchant_id" class="valid" lay-filter="select">
                            <option value="">选择商户</option>
                            @foreach($merchant as $v)
                            <option value="{{$v->mer_id}}">{{$v->mer_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>下发银行：
                    </label>
                    <div class="layui-input-block">
                        <select id="" name="" class="valid" lay-filter="select">
                            <option value="">选择下发银行</option>
                            @foreach($bankNoName as $v)
                            <option value="{{$v->bank_id}}">{{$v->bankAccountName}}--{{$v->bankAccountNo}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>银行详情：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="bankCode" name="bankCode" autocomplete="off" class="layui-input" value="">
                        <input type="text" id="bankAccountNo" name="bankAccountNo" autocomplete="off" class="layui-input" value="">
                        <input type="text" id="bankAccountName" name="bankAccountName" autocomplete="off" class="layui-input" value="">
                        <!-- <input type="text" id="notifyUrl" name="notifyUrl" autocomplete="off" class="layui-input" value=""> -->
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="remarks" class="layui-form-label">
                        <span class="x-red">*</span>备注：
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="remarks" name="remarks" 
                        autocomplete="off" class="layui-input" value="及时备注">
                    </div>
                   
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="add" lay-submit="">
                        下发
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

        form.on('submit(add)',function(data){   
            $.ajax({
                url:'/admin/order',
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

