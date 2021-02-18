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
 * 撤销订单
 * @method string getOutTradeNo() 获取订单号
 * @method void setOutTradeNo($outTradeNo) 设置订单号
 * @method bool hasOutTradeNo() 是否设置订单号
 * @method void setTransactionId($transactionId) 设置交易流水号
 * @method bool hasTransactionId() 是否设置交易流水号
 */
class ReverseInput extends Input{
	
	/**
	 * 获取交易流水号
	 *
	 * @return string
	 */
	public function getTransactionId(){
		return $this->get('transaction_id');
	}
	
}
