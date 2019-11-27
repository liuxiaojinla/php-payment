<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment\entity;

/**
 * 关闭订单
 * @method void setOutTradeNo($outTradeNo) 设置订单号
 * @method bool hasOutTradeNo() 是否设置订单号
 *
 * @package xin\payment\input
 */
class CloseOrderInput extends PaymentInput{

	/**
	 * 获取订单号
	 *
	 * @return string
	 */
	public function getOutTradeNo(){
		return $this->get(['out_trade_no']);
	}

}
