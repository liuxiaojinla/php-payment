<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 16:58
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentInput;
use xin\payment\entity\ReverseOutput;
use xin\payment\PaymentException;

/**
 * Class Reverse 撤销订单API接口
 * out_trade_no和transaction_id必须填写一个
 * appid、mchid、spbill_create_ip、nonce_str不需要填入
 *
 * @package xin\payment\func
 */
class Reverse extends BaseFunc{

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
		if(!$input->hasOutTradeNo() && !$input->hasTransactionId()){
			throw new PaymentException("撤销订单API接口中，参数out_trade_no和transaction_id必须填写一个！");
		}

		// 初始化input
		WxPayUtil::initWxPayInput($config, $input);

		// 设置支付签名
		$sign = WxPayUtil::makeSign($input->toArray(), $config->getWxPayKey());
		$input->setSign($sign);

		$url = "https://api.mch.weixin.qq.com/secapi/pay/reverse";
		$response = WxPayUtil::request($url, $input->toXml());
		$result = WxPayUtil::makeOutput($response, ReverseOutput::class, $config);

		return $result->transformKeys([]);
	}
}
