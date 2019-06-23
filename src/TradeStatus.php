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
 * 交易状态
 *
 * @package xin\payment
 */
interface TradeStatus{

	/**
	 * 用户支付中
	 */
	const USER_PAYING = 'USERPAYING';

	/**
	 * 用户支付失败
	 */
	const PAY_ERROR = 'PAYERROR';

	/**
	 * 用户支付成功
	 */
	const PAY_SUCCESS = 'SUCCESS';

	/**
	 * 未支付
	 */
	const NOT_PAY = 'NOTPAY';

	/**
	 * 交易处理中
	 */
	const PROCESSING = 'PROCESSING';

	/**
	 * 已关闭
	 */
	const CLOSED = 'CLOSED';

	/**
	 * 已撤销
	 */
	const REVOKED = 'REVOKED';

	/**
	 * 已退款
	 */
	const REFUND_SUCCESS = 'REFUND_SUCCESS';

	/**
	 * 退款失败
	 */
	const REFUND_FAIL = 'REFUND_FAIL';

	/**
	 * 未知状态
	 */
	const UNKNOWN = 'UNKNOWN';
}
