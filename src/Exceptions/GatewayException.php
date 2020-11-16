<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */
namespace Xin\Payment\Exceptions;

class GatewayException extends Exception{
	
	/**
	 * Bootstrap.
	 *
	 * @param string       $message
	 * @param array|string $raw
	 * @param int          $code
	 * @author yansongda <me@yansonga.cn>
	 */
	public function __construct($message, $raw = [], $code = self::ERROR_GATEWAY){
		parent::__construct('ERROR_GATEWAY: '.$message, $raw, $code);
	}
}
