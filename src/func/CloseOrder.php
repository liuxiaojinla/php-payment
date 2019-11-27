<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 16:54
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\CloseOrderOutput;
use xin\payment\entity\PaymentInput;
use xin\payment\entity\UnifiedOrderOutput;
use xin\payment\PaymentException;

/**
 * Class CloseOrder 关闭订单
 * out_trade_no 必填
 * appid、mchid、spbill_create_ip、nonce_str不需要填入
 *
 * @package xin\payment\func
 */
class CloseOrder extends BaseFunc{

	/**
	 * 支付宝
	 *
	 * @param \xin\payment\ConfigInterface     $config
	 * @param \xin\payment\entity\PaymentInput $input
	 * @return mixed
	 */
	protected function onAliPay(ConfigInterface $config, PaymentInput $input){
		// TODO: Implement onAliPay() method.
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
		if(!$input->hasOutTradeNo()){
			throw new PaymentException("订单查询接口中，out_trade_no必填！");
		}

		$url = "https://api.mch.weixin.qq.com/pay/closeorder";
		$response = WxPayUtil::request($url, $input->toXml());
		$result = WxPayUtil::makeOutput($response, CloseOrderOutput::class, $config);

		return $result->transformKeys([]);
	}
}
