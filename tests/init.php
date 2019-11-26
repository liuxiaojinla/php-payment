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

		public function getWechatAppId(){
			return "";
		}

		public function getWechatMchId(){
			return "";
		}

		public function getWechatKey(){
			return "";
		}
	});
}
