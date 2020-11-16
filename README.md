# 统一化支付 V4.0

#### 介绍
你还在为微信支付或支付宝支付编写两套不同逻辑的代码而头疼吗？你还在为庞杂的参数记忆而苦恼吗？

让我们回归本源，重新定义统一支付器。


#### 软件架构
使用工厂模式进行统一管理不同驱动【目前已适配EasyPay】，使用者无需知道工厂类是如何构建一个支付驱动实例；
让我们更多的去关心业务层的输入/输出，框架本身实现了一套参数转换器，使用者只需调用统一输入/输出的参数即可；

#### 安装教程

`composer require xin/payment`

#### API集成计划

- ~~统一下单~~
- ~~订单查询~~
- ~~申请退款~~
- ~~退款查询~~
- ~~关闭订单~~
- ~~撤销订单~~
- ~~扫码支付~~
- 企业打款到零钱 （进行中）
- 查询企业打款到零钱 （进行中）
- 企业打款到银行卡 （进行中）
- 请求单次分账 （排期中）
- 请求多次分账 （排期中）
- 查询分账结果 （排期中）
- 添加分账接收方 （排期中）
- 删除分账接收方 （排期中）
- 完结分账 （排期中）
- 分账回退 （排期中）


#### 使用说明
**统一下单**

```php
$orderSn = time();
var_dump($orderSn);

//Pay::wechat()->miniapp();

$input = new UnifiedOrderInput();
$input->setOutTradeNo($orderSn);
$input->setBody('测试支付');
$input->setTotalFee(100);
$input->setNotifyUrl('https://www.baidu.com');
$input->setOpenid('o49390NOh_fmsdpZCEgoWbC_8nws');

$input->setChannel(PayChannel::WECHAT);
//$input->setChannel(PayChannel::ALIPAY);

$input->setTradeType(TradeType::JSAPI);
$input->setTradeType(TradeType::NATIVE);
$input->setTradeType(TradeType::MINI_APP);
//$input->setTradeType(TradeType::APP);
//$input->setTradeType(TradeType::WAP);

//$input->setTradeType(TradeType::SCAN);
//$input->setAuthCode('1354804793001231564897');

$result = payment()->unifiedOrder($input);

if($result->getTradeType() === TradeType::JSAPI || TradeType::MINI_APP === $result->getTradeType()){
var_dump("appid:".$result->appId);
var_dump($result->toArray());
}else{
var_dump("appid:".$result->getAppid());
var_dump("mchid:".$result->getMchId());
var_dump("nonce_str:".$result->getNonceStr());
var_dump("prepay_id:".$result->getPrepayId());
var_dump("code_url:".$result->getCodeUrl());
}
```

**查询订单**

```php
$input = new OrderQueryInput();
$input->setOutTradeNo(1605547747);
$input->setChannel(PayChannel::WECHAT);
$result = payment()->orderQuery($input);

var_dump("appid:".$result->getAppid());
var_dump("mchid:".$result->getMchId());
var_dump("total_fee:".$result->getTotalFee());
var_dump("out_trade_no:".$result->getOutTradeNo());
var_dump("trade_state:".$result->getTradeState());
var_dump("trade_state_desc:".$result->getTradeStateDesc());
var_dump($result->toArray());
```

**退款**

```php
$input = new RefundInput();
$input->setOutTradeNo('1561279171');
$input->setOutRefundNo($orderSn);
$input->setTotalFee(100);
$input->setRefundFee(100);
$input->setOpUserId(100);
$input->setChannel(PayChannel::WECHAT);

$result = payment()->refund($input);

var_dump("appid:".$result->getAppid());
var_dump("mchid:".$result->getMchId());
var_dump("total_fee:".$result->getTotalFee());
var_dump("out_trade_no:".$result->getOutTradeNo());
var_dump($result->toArray());
```
