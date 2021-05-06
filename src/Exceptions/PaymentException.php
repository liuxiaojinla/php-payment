<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */
namespace Xin\Payment\Exceptions;

class PaymentException extends \Exception{

	const UNKNOWN_ERROR = 9999;

	const INVALID_GATEWAY = 1;

	const INVALID_CONFIG = 2;

	const INVALID_ARGUMENT = 3;

	const ERROR_GATEWAY = 4;

	const INVALID_SIGN = 5;

	const ERROR_BUSINESS = 6;

	/**
	 * Raw error info.
	 *
	 * @var array
	 */
	public $raw;

	/**
	 * @param string       $message
	 * @param array|string $raw
	 * @param int|string   $code
	 */
	public function __construct($message = '', $raw = [], $code = self::UNKNOWN_ERROR){
		$message = '' === $message ? 'Unknown Error' : $message;
		$this->raw = is_array($raw) ? $raw : [$raw];

		parent::__construct($message, intval($code));
	}
}
