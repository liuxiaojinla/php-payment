<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Bus;

/**
 * 统一下单结果
 *
 * @property-read string appId
 * @property-read string timeStamp
 * @property-read string timestamp
 * @property-read string nonceStr
 * @property-read string package
 * @property-read string signType
 * @property-read string paySign
 * @method string getNonceStr()
 * @method bool hasNonceStr()
 * @method string getPrepayId()
 * @method bool hasPrepayId()
 * @method string getCodeUrl()
 * @method bool hasCodeUrl()
 */
class UnifiedOrderNotify extends NotifyResult{

}
