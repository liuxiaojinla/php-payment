<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

interface TradeType{

	/**
	 * 数据键名
	 */
	const __NAME__ = '__trade_type__';

	/**
	 * 原生类型
	 */
	const NATIVE = 'native';

	/**
	 * 公众号、服务号支付
	 */
	const JSAPI = 'jsapi';

	/**
	 * 小程序支付
	 */
	const MINI = 'mini';

	/**
	 * APP 类型
	 */
	const APP = 'app';

	/**
	 * 简易类型 （h5跳转）
	 */
	const WEB = 'web';

}
