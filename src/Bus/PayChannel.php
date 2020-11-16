<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */
namespace Xin\Payment\Bus;

/**
 * 支付渠道
 */
interface PayChannel{
	
	/**
	 * 微信
	 */
	const WECHAT = 'wechat';
	
	/**
	 * 支付宝
	 */
	const ALIPAY = 'alipay';
}
