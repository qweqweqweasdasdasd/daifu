@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <input type="hidden" name="bank_id" value="{{$bank->bank_id}}">
                <div class="layui-form-item">
                    <label for="bankCode" class="layui-form-label">
                    <span class="x-red">*</span>银行编码</label>
                    <div class="layui-input-inline">
                        <select name="bankCode">
                            <option>请选择银行</option>
                            @foreach($bank_list as $k=>$v)
                            <option value="{{$v}}" @if($v == $bank->bankCode) selected @endif>{{$k}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="bankAccountNo" class="layui-form-label">
                        <span class="x-red">*</span>银行账号
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="bankAccountNo" name="bankAccountNo"
                        autocomplete="off" class="layui-input" value="{{$bank->bankAccountNo}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="bankAccountName" class="layui-form-label">
                        <span class="x-red">*</span>持卡人名字
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="bankAccountName" name="bankAccountName"
                        autocomplete="off" class="layui-input" value="{{$bank->bankAccountName}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>状态</label>
                    <div class="layui-input-block">
                    <input type="radio" name="bank_status" value="1" title="启用"  @if($bank->bank_status == 1) checked @endif >
                    <input type="radio" name="bank_status" value="2" title="停用"  @if($bank->bank_status == 2) checked @endif >
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="remarks" class="layui-form-label">描述</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" id="remarks" name="remarks" class="layui-textarea">{{$bank->remarks}}</textarea>
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
            var id = $('input[name="bank_id"]').val();
            //debugger;
            // ajax
            $.ajax({
                url:'/admin/bank/'+id,
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
                    if(res.code == '6001'){
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

