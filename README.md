# xin-payment

#### 介绍
统一化微信支付、支付宝支付API，目前仅适配了微信支付


#### 软件架构
使用工厂模式进行统一管理不同驱动，使用者无需知道工厂类是如何构建一个支付驱动实例

#### 安装教程

`composer require xin/payment`

#### 使用说明
**统一下单**

    `$orderSn = time();
    var_dump($orderSn);
    
    $input = new UnifiedOrderOptions();
    $input->setSubAppid('wx12345678910');
    $input->setSubOpenid('o9F2bs2V9FUlaoeggfIo94YRWVS4');
    $input->setOutTradeNo($orderSn);
    $input->setBody('测试支付');
    $input->setTotalFee(100);
    $input->setNotifyUrl('https://www.baidu.com');
    $input->setOpenid('o9F2bs2V9FUlaoeggfIo94YRWVS4');
    $input->setPayType(PayType::WECHAT);
    $input->setTradeType(TradeType::JSAPI);
    
    try{
    	$payment = get_payment();
    	$result = $payment->unifiedOrder($input);
    	var_dump($result);
    }catch(PaymentException $e){
    	var_dump("error:".$e->getMessage());
    }`

**查询订单**

    `$input = new OrderQueryOptions();
    $input->setOutTradeNo(1561279171);
    $input->setPayType(PayType::WECHAT);
    
    try{
        $payment = get_payment();
        $result = $payment->orderQuery($input);
        var_dump($result);
    }catch(PaymentException $e){
        var_dump("error:".$e->getMessage());
    }`

**退款**

    `$orderSn = time();
    var_dump($orderSn);
    
    $input = new RefundOptions();
    $input->setOutTradeNo('1561279171');
    $input->setOutRefundNo($orderSn);
    $input->setTotalFee(100);
    $input->setRefundFee(100);
    $input->setOpUserId(100);
    $input->setPayType(PayType::WECHAT);
    
    try{
        $payment = get_payment();
        $result = $payment->refund($input);
        var_dump($result);
    }catch(PaymentException $e){
        var_dump("error:".$e->getMessage());
    }`
