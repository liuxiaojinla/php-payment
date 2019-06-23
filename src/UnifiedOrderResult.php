<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

;

/**
 * 统一下单结果
 *
 * @package xin\payment\data
 */
class UnifiedOrderResult extends PaymentResult{

	/**
	 * 获取JS支付信息
	 *
	 * @return array
	 */
	public function getJsPayInfo(){
		return $this->get('__jspay_info__');
	}
}
