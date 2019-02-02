<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment\data;

use xin\payment\PaymentData;

/**
 * 请求参数
 *
 * @package xin\payment\data
 */
class PaymentOptions extends PaymentData{

	/**
	 * 设置数据签名
	 *
	 * @param string|callable $sign
	 */
	public function setSign($sign){
		if(is_callable($sign)){
			$sign = call_user_func($sign, $this->options);
		}
		$this->set('sign', $sign);
	}
}
