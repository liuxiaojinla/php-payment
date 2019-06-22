<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

/**
 * 支付引擎类型
 *
 * @package xin\payment
 */
final class PayType{

	/**
	 * 数据键名
	 */
	const __NAME__ = '__pay_type__';

	/**
	 * 微信
	 */
	const WECHAT = 'wechat';

	/**
	 * 支付宝
	 */
	const ALIPAY = 'alipay';

	/**
	 * PayType constructor.
	 */
	protected function __construct(){ }
}
