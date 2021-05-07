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
 * @property-read string    appId
 * @property-read string    attach
 * @property-read string    bankType
 * @property-read string    feeType
 * @property-read string    mchId
 * @property-read string    signType
 * @property-read string    openid
 * @property-read float|int totalFee
 * @method string getAttach()
 * @method bool hasAttach()
 * @method string getBankType()
 * @method bool hasBankType()
 * @method string getFeeType()
 * @method bool hasFeeType()
 * @method string getMchId()
 * @method bool hasMchId()
 * @method string getOpenid()
 * @method bool hasOpenid()
 * @method string getTotalFee()
 * @method bool hasTotalFee()
 */
class UnifiedOrderNotify extends NotifyResult{

}
