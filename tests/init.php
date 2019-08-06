<?php
use xin\payment\Payment;

/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/23 16:36
 */

function get_payment(){
	return Payment::basic([
		'wechat' => [
			'appid'  => '',
			'mch_id' => '',
			'key'    => '',
		],
	]);
}
