@extends('admin/common/layout')

@section('content')

<body>
<div class="layui-fluid">
<div class="layui-row layui-col-space15">
    <div class="layui-col-md12">
        <div class="layui-card">
            <div class="layui-card-header">
                下发订单详情
            </div>
            <div class="layui-card-body ">
                <ul class="layui-timeline">
                <li class="layui-timeline-item">
                    <i class="layui-icon layui-timeline-axis">&#xe63f;</i>
                    <div class="layui-timeline-content layui-text">
                    <h3 class="layui-timeline-title">{{$order->created_at}}</h3>
                    <p>下发订单号: {{$order->merOrderNo}}<br>
                        下发金额: {{$order->amount}}<br>
                        下发操作者: {{$order->operator}}<br>
                        下发状态: {!! xiafa_order_show_status($order->order_status) !!}<br>
                        下发备注: {{$order->remarks}}<br>
                        银行详情: {{$order->bank_info}}<br>

                    </p>
                    </div>
                </li>
                </ul>
        </div>
        </div>
    </div>
    </div>
</div>
</body>

@endsection

@section('my-js')

@endsection

