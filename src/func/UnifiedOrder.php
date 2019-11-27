<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 14:36
 */

namespace xin\payment\func;

use xin\payment\ConfigInterface;
use xin\payment\entity\PaymentInput;
use xin\payment\entity\UnifiedOrderOutput;
use xin\payment\PaymentException;
use xin\payment\TradeType;
use xin\payment\Util;

class UnifiedOrder extends BaseFunc{

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
		if($input->getTradeType() == "JSAPI"){
			if($input->has('sub_appid') && !$input->has('sub_openid')){
				throw new PaymentException("统一支付接口中，缺少必填参数sub_openid！sub_appid不为空时，sub_openid为必填参数！");
			}

			if(!$input->has('openid')){
				throw new PaymentException("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");
			}
		}

		if($input->getTradeType() == "NATIVE" && !$input->has('product_id')){
			throw new PaymentException("统一支付接口中，缺少必填参数product_id！trade_type为NATIVE时，product_id为必填参数！");
		}

		// 转换keys
		$input = $input->transformKeys([
			'start_time'        => 'time_start',
			'expire_time'       => 'time_expire',
			TradeType::__NAME__ => function($value){
				return ['trade_type', strtoupper($value)];
			},
		]);

		// 检查数据是否存在
		$input->check([
			'out_trade_no', 'body',
			'total_fee', 'trade_type',
		]);

		//终端ip
		$input->set('spbill_create_ip', isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');

		// 设置支付签名
		$sign = WxPayUtil::makeSign($input->toArray(), $config->getWechatKey());
		$input->setSign($sign);

		// 发起请求
		$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
		$response = WxPayUtil::request($url, $input->toXml());
		$result = WxPayUtil::makeOutput($response, UnifiedOrderOutput::class, $config);

		// 公众号、服务号、小程序支付
		if($input->get('trade_type') == 'JSAPI'){
			$this->buildWxPayJsParameters($result, $config);
		}

		return $result->transformKeys([]);
	}

	/**
	 * 生成JS调取收银台
	 *
	 * @param UnifiedOrderOutput           $result
	 * @param \xin\payment\ConfigInterface $config
	 * @throws \xin\payment\func\WxPayException
	 */
	private function buildWxPayJsParameters(UnifiedOrderOutput $result, ConfigInterface $config){
		if(!$result->has('appid')
			|| !$result->has('prepay_id')
			|| $result->get('prepay_id') == ""){
			throw new WxPayException("订单信息错误", 511);
		}

		$info = [
			'appId'     => isset($result["sub_appid"]) ? $result["sub_appid"] : $result["appid"],
			'timeStamp' => time(),
			'nonceStr'  => Util::nonceStr(),
			'package'   => "prepay_id=".$result['prepay_id'],
			'signType'  => "MD5",
		];
		$info['paySign'] = WxPayUtil::makeSign($info, $config->getWechatKey());

		$result->set('__jspay_info__', $info);
	}
}
