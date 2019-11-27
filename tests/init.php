<?php
use xin\payment\ConfigInterface;
use xin\payment\Payment;

/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/23 16:36
 */

function get_payment(){
	//	[
	//		'wechat' => [
	//			'appid'  => '',
	//			'mch_id' => '',
	//			'key'    => '',
	//		],
	//	]
	return new Payment(new class implements ConfigInterface{

		public function getWxPayAppId(){
			return "";
		}

		/**
		 * 返回 微信支付 mch_id
		 *
		 * @return string
		 */
		public function getWxPayMchId(){
			// TODO: Implement getWxPayMchId() method.
		}

		/**
		 * 返回微信支付 支付密钥
		 *
		 * @return string
		 */
		public function getWxPayKey(){
			// TODO: Implement getWxPayKey() method.
		}
	});
}
