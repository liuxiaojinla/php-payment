<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace Xin\Payment\Bus\Base;

use Xin\Payment\Bus\Result;

/**
 * 统一下单结果
 *
 * @property-read string                                  appId
 * @property-read string                                  timeStamp
 * @property-read string                                  timestamp
 * @property-read string                                  nonceStr
 * @property-read string                                  package
 * @property-read string                                  signType
 * @property-read string                                  paySign
 * @method string getNonceStr()
 * @method bool hasNonceStr()
 * @method string getPrepayId()
 * @method bool hasPrepayId()
 * @method string getCodeUrl()
 * @method bool hasCodeUrl()
 * @property-read \Xin\Payment\Bus\Base\UnifiedOrderInput $input
 */
class UnifiedOrderResult extends Result{
	
	/**
	 * @return string
	 */
	public function getTradeType(){
		if(!$this->input){
			return '';
		}
		
		return $this->input->getTradeType();
	}
}
