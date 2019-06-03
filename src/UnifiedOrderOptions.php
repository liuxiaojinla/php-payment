<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

;

/**
 * 统一下单参数
 * @method string getOutTradeNo() 获取订单号
 * @method void setOutTradeNo($outTradeNo) 设置订单号
 * @method bool hasOutTradeNo() 是否设置订单号
 * @method string getBody() 获取订单详情
 * @method void setBody($body) 设置订单详情
 * @method bool hasBody() 是否设置订单详情
 * @method string getTotalFee() 获取订单金额
 * @method void setTotalFee($totalFee) 设置订单金额
 * @method bool hasTotalFee() 是否设置订单金额
 * @method string getTradeType() 获取订单类型
 * @method void setTradeType($tradeType) 设置订单类型
 * @method bool hasTradeType() 是否设置订单类型
 * @method string getNotifyUrl() 获取回调地址
 * @method void setNotifyUrl($notifyUrl) 设置回调地址
 * @method bool hasNotifyUrl() 是否设置回调地址
 * @method string getAttach() 获取附加数据
 * @method void setAttach($attach) 设置附加数据
 * @method bool hasAttach() 是否设置附加数据
 * @method string getSignType() 获取签名类型
 * @method void setSignType($signType) 设置签名类型
 * @method bool hasSignType() 是否设置签名类型
 * @method string getDetail() 获取商品详情
 * @method void setDetail($detail) 设置商品详情
 * @method bool hasDetail() 是否设置商品详情
 * @method string getFeeType() 获取标价币种
 * @method void setFeeType($feeType) 设置标价币种
 * @method bool hasFeeType() 是否设置标价币种
 * @method string getStartTime() 获取交易起始时间
 * @method void setStartTime($startTime) 设置交易起始时间
 * @method bool hasStartTime() 是否设置交易起始时间
 * @method string getExpireTime() 获取交易结束时间
 * @method void setExpireTime($expireTime) 设置交易结束时间
 * @method bool hasExpireTime() 是否设置交易结束时间
 * @method string getProductId() 获取商品ID
 * @method void setProductId($productId) 设置商品ID
 * @method bool hasProductId() 是否设置商品ID
 * @method string getLimitPay() 获取指定支付方式
 * @method void setLimitPay($limitPay) 设置指定支付方式 上传此参数no_credit--可限制用户不能使用信用卡支付
 * @method bool hasLimitPay() 是否设置指定支付方式
 * @method string getOpenid() 获取用户标识
 * @method void setOpenid($openid) 设置用户标识
 * @method bool hasOpenid() 是否设置用户标识
 *
 * @package xin\payment\data
 */
class UnifiedOrderOptions extends PaymentOptions{

}
