<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 14:29
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\CloseOrderOutput;
use xin\payment\entity\OrderQueryInput;
use xin\payment\entity\OrderQueryOutput;
use xin\payment\entity\PaymentInput;
use xin\payment\PaymentException;

/**
 * Class OrderQuery 查询订单
 *
 * @package xin\payment\func
 */
class OrderQuery extends BaseFunc{

	/**
	 * 支付宝
	 *
	 * @param \xin\payment\ConfigInterface     $config
	 * @param \xin\payment\entity\PaymentInput $options
	 * @return mixed
	 */
	protected function onAliPay(ConfigInterface $config, PaymentInput $options){
		// TODO: Implement onAlipay() method.
	}

	/**
	 * 微信
	 *
	 * @param \xin\payment\ConfigInterface     $config
	 * @param \xin\payment\entity\PaymentInput $input
	 * @return mixed
	 * @throws \xin\payment\PaymentException
	 */
	protected function onWxPay(ConfigInterface $config, PaymentInput $input){
		if(!$input->hasOutTradeNo() && !$input->hasTransactionId()){
			throw new PaymentException("订单查询接口中，out_trade_no、transaction_id至少填一个！");
		}

		$url = "https://api.mch.weixin.qq.com/pay/orderquery";
		$response = WxPayUtil::request($url, $input->toXml());
		$result = WxPayUtil::makeOutput($response, OrderQueryOutput::class, $config);

		return $result->transformKeys([]);
	}
}
