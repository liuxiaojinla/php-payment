<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace Xin\Payment\Bus\Base;

use Xin\Payment\Bus\Output;

/**
 * 申请退款结果
 * @method string getOutTradeNo() 获取订单号
 * @method string getTransactionId() 获取设置交易流水号
 * @method string getTotalFee() 获取订单金额
 * @method int getRefundFee() 获取订单退款金额
 * @method int getOpUserId() 获取操作管理员ID
 */
class RefundOutput extends Output{

}
