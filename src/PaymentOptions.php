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
 * 请求参数
 * @method string getPayType() 获取支付类型
 * @method string setPayType($payType) 设置支付类型
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
			$sign = call_user_func($sign, $this->data);
		}
		$this->set('sign', $sign);
	}
}
