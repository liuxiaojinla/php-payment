<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment\data;

/**
 * 申请退款
 * @method void setOutTradeNo($outTradeNo) 设置订单号
 * @method bool hasOutTradeNo() 是否设置订单号
 * @method void setOutRefundNo($outRefundNo) 设置退款订单号
 * @method bool hasOutRefundNo() 是否设置退款订单号
 * @method void setTransactionId($transactionId) 设置交易流水号
 * @method bool hasTransactionId() 是否设置交易流水号
 * @method void setTotalFee($totalFee) 设置订单金额
 * @method bool hasTotalFee() 是否设置订单金额
 * @method void setRefundFee($refundFee) 设置订单退款金额
 * @method bool hasRefundFee() 是否设置订单退款金额
 * @method void setOpUserId($opUserId) 设置操作管理员ID
 * @method bool hasOpUserId() 是否设置操作管理员ID
 *
 * @package xin\payment\data
 */
class RefundPaymentOptions extends PaymentOptions{

	/**
	 * 获取订单号
	 *
	 * @return string
	 */
	public function getOutTradeNo(){
		return $this->get(['out_trade_no']);
	}

	/**
	 * 获取退款订单号
	 *
	 * @return string
	 */
	public function getOutRefundNo(){
		return $this->get(['out_refund_no']);
	}

	/**
	 * 获取交易流水号
	 *
	 * @return string
	 */
	public function getTransactionId(){
		return $this->get(['transaction_id']);
	}

	/**
	 * 获取订单金额
	 *
	 * @return string
	 */
	public function getTotalFee(){
		return $this->get(['total_fee']);
	}

	/**
	 * 获取订单退款金额
	 *
	 * @return string
	 */
	public function getRefundFee(){
		return $this->get(['refund_fee']);
	}

	/**
	 * 获取操作管理员ID
	 *
	 * @return string
	 */
	public function getOpUserId(){
		return $this->get(['op_user_id']);
	}

}
