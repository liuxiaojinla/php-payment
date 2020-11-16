<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace Xin\Payment\Bus\Base;

use Xin\Payment\Bus\Input;

/**
 * 申请退款
 * @method string getOutTradeNo() 获取订单号
 * @method void setOutTradeNo($outTradeNo) 设置订单号
 * @method bool hasOutTradeNo() 是否设置订单号
 * @method string getOutRefundNo() 获取退款订单号
 * @method void setOutRefundNo($outRefundNo) 设置退款订单号
 * @method bool hasOutRefundNo() 是否设置退款订单号
 * @method string getTransactionId() 获取设置交易流水号
 * @method void setTransactionId($transactionId) 设置交易流水号
 * @method bool hasTransactionId() 是否设置交易流水号
 * @method int getTotalFee() 获取订单金额
 * @method void setTotalFee($totalFee) 设置订单金额
 * @method bool hasTotalFee() 是否设置订单金额
 * @method int getRefundFee() 获取订单退款金额
 * @method void setRefundFee($refundFee) 设置订单退款金额
 * @method bool hasRefundFee() 是否设置订单退款金额
 * @method int getOpUserId() 获取操作管理员ID
 * @method void setOpUserId($opUserId) 设置操作管理员ID
 * @method bool hasOpUserId() 是否设置操作管理员ID
 */
class RefundInput extends Input{

}
