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
 * 支付异常
 *
 * @package xin\payment
 */
class PaymentException extends \Exception{

	/**
	 * 获取错误原因
	 *
	 * @return string
	 */
	public function getReason(){
		return null;
	}

	/**
	 * 获取错误解决方案
	 *
	 * @return string
	 */
	public function getResole(){
		return null;
	}
}
