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
 * 退款查询
 * @method string getOutTradeNo() 获取订单号
 * @method void setOutTradeNo($outTradeNo) 设置订单号
 * @method bool hasOutTradeNo() 是否设置订单号
 * @method string getTransactionId() 获取设置交易流水号
 * @method void setTransactionId($transactionId) 设置交易流水号
 * @method bool hasTransactionId() 是否设置交易流水号
 * @method string getOutRefundNo() 获取退款订单号
 * @method void setOutRefundNo($outRefundNo) 设置退款订单号
 * @method bool hasOutRefundNo() 是否设置退款订单号
 * @method string getRefundId() 获取退款订单ID
 * @method void setRefundId($outRefundId) 设置退款订单ID
 * @method bool hasRefundId() 是否设置退款订单ID
 */
class RefundQueryInput extends Input{

}
