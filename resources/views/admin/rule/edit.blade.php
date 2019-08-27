@extends('admin/common/layout')

@section('content')

<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
            <input type="hidden" name="rule_id" value="{{$rule->rule_id}}">
                <div class="layui-form-item">
                    <label for="rule_name" class="layui-form-label">
                        <span class="x-red">*</span>权限名称</label>
                    <div class="layui-input-block">
                        <input type="text" id="rule_name" name="rule_name"  autocomplete="off" class="layui-input" value="{{$rule->rule_name}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="pid" class="layui-form-label">
                        <span class="x-red">*</span>父ID</label>
                    <div class="layui-input-block">
                        <select name="pid">
                            <option value="0">/</option>
                            @foreach($getRuletree as $v)
                            <option value="{{$v['rule_id']}}" @if($rule->pid == $v['rule_id']) selected @endif>{{$v['rule_name_text']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="route" class="layui-form-label">
                        <span class="x-red">*</span>路由</label>
                    <div class="layui-input-block">
                        <input type="text" id="route" name="route"  autocomplete="off" class="layui-input" value="{{$rule->route}}"></div>
                </div>
                <div class="layui-form-item">
                    <label for="rule_c" class="layui-form-label">
                        <span class="x-red">*</span>控制器</label>
                    <div class="layui-input-block">
                        <input type="text" id="rule_c" name="rule_c"  autocomplete="off" class="layui-input" value="{{$rule->rule_c}}"></div>
                </div>
                <div class="layui-form-item">
                    <label for="rule_a" class="layui-form-label">
                        <span class="x-red">*</span>方法</label>
                    <div class="layui-input-block">
                        <input type="text" id="rule_a" name="rule_a" autocomplete="off" class="layui-input" value="{{$rule->rule_a}}"></div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>是否显示</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_show" value="1" title="显示" @if($rule->is_show == 1) checked @endif>
                        <input type="radio" name="is_show" value="2" title="隐藏" @if($rule->is_show == 2) checked @endif>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>菜单类型</label>
                    <div class="layui-input-block">
                        <input type="radio" name="is_verify" value="1" title="验证" @if($rule->is_verify == 1) checked @endif>
                        <input type="radio" name="is_verify" value="2" title="不验证" @if($rule->is_verify == 2) checked @endif>
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="remark" class="layui-form-label">描述</label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容" id="remark" name="remark" class="layui-textarea">{{$rule->remark}}</textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label"></label>
                    <button class="layui-btn" lay-filter="update" lay-submit="">更新</button></div>
            </form>
        </div>
    </div>
</body>

@endsection

@section('my-js')
<script>
    layui.use(['form','layer'],function(){
        var $ = layui.jquery;
        var form = layui.form;
        var layer = layui.layer;

        // 监听提交
        form.on('submit(update)',function(data){
            console.log(data.field);
            var id = $('input[name="rule_id"]').val();

            //ajax
            $.ajax({
                url:'/admin/rule/'+id,
                data:data.field,
                dataType:'json',
                type:'PATCH',
                headers:{
                    'X-CSRF-TOKEN':"{{csrf_token()}}"
                },
                success:function(res){
                    if(res.code == '1'){
                        layer.alert(res.msg,{icon:6},function(){
                            window.parent.location.reload();        
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        })
                    }
                    if(res.code == '5001'){
                        layer.msg(res.msg,{icon:5})
                    }
                    if(res.code == '422'){
                        layer.msg(res.msg);
                    }

                }
            })
            return false;
        })

        // 
    })
</script>
@endsection

