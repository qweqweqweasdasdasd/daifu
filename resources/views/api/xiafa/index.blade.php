<!DOCTYPE html>
<html>
<head lang="en">

    <title>提交测试</title>

</head>
<body>
<div class="container">
  
    ----------------------------代付订单提交---------------------------------------
    <br/><br/>

    <form id="remitOrderForm" action="/api/remitSubmit" method="post">
        商户订单号：<input name="merOrderNo" type="text" value="1111111111111111111111"/><br/>
        金额：<input name="amount" type="text" value="1"/><br/>
        银行编码：<input name="bankCode" type="text" value="ABC"/><br/>
        银行账号：<input name="bankAccountNo" type="text" value="6228430779542075379"/><br/>
        持卡人名字：<input name="bankAccountName" type="text" value="刘文山"/><br/>
        回调地址：<input name="notifyUrl" type="text" value="http://127.0.0.1"/><br/>
        备注：<input name="remarks" type="text" value="备注"/><br/>
        <button type="submit">代付订单提交</button>
    </form>

</div>
</body>
</html>