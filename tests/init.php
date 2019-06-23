<?php
use xin\payment\PaymentFactory;

/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/23 16:36
 */

function get_payment(){
	return PaymentFactory::factoryBasic([
		'wechat' => [
			'appid'  => '',
			'mch_id' => '',
			'key'    => '',
		],
	]);
}
