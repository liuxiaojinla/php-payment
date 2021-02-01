<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */
namespace Xin\Payment\Exceptions;

class InvalidGatewayException extends PaymentException{
	
	/**
	 * @param string       $message
	 * @param array|string $raw
	 */
	public function __construct($message, $raw = []){
		parent::__construct('INVALID_GATEWAY: '.$message, $raw, self::INVALID_GATEWAY);
	}
}
