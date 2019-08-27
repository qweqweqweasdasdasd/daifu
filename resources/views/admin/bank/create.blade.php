@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label for="bankCode" class="layui-form-label">
                    <span class="x-red">*</span>银行编码</label>
                    <div class="layui-input-inline">
                        <select name="bankCode">
                            <option>请选择银行</option>
                            @foreach($bank_list as $k=>$v)
                            <option value="{{$v}}">{{$k}}</option>
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
                        autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="bankAccountName" class="layui-form-label">
                        <span class="x-red">*</span>持卡人名字
                    </label>
                    <div class="layui-input-block">
                        <input type="text" id="bankAccountName" name="bankAccountName"
                        autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>状态</label>
                    <div class="layui-input-block">
                    <input type="radio" name="bank_status" value="1" title="启用" checked="" >
                    <input type="radio" name="bank_status" value="2" title="停用" >
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="remarks" class="layui-form-label">描述</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" id="remarks" name="remarks" class="layui-textarea"></textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="add" lay-submit="">
                        增加
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
            console.log(data);
            // ajax
            $.ajax({
                url:'/admin/bank',
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
                    if(res.code == '6000'){
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

