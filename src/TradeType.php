<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */
namespace Xin\Payment;

final class TradeType{
	
	/**
	 * 原生支付二维码
	 */
	const NATIVE = 'NATIVE';
	
	/**
	 * 付款码支付
	 */
	const SCAN = 'SCAN';
	
	/**
	 * 公众号、服务号支付
	 */
	const JSAPI = 'JSAPI';
	
	/**
	 * 小程序支付
	 */
	const MINI_APP = 'MINI_APP';
	
	/**
	 * APP 类型
	 */
	const APP = 'APP';
	
	/**
	 * 简易类型 （h5跳转）
	 */
	const WAP = 'MWEB';
	
}
