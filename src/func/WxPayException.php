<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */
namespace xin\payment\func;

use Throwable;
use xin\payment\PaymentException;

/**
 * 微信支付异常
 *
 * @package xin\payment\func
 */
class WxPayException extends PaymentException{

	/**
	 * 微信错误码
	 *
	 * @var string
	 */
	private $errCode;

	/**
	 * 微信结果错误列表
	 *
	 * @var array
	 */
	protected static $ERROR = [
		'INVALID_REQUEST'       => [
			'reason'  => '参数格式有误或者未按规则上传',
			'resolve' => '订单重入时，要求参数值与原请求一致，请确认参数问题',
		],
		'NOAUTH'                => [
			'reason'  => '商户未开通此接口权限',
			'resolve' => '请商户前往申请此接口权限',
		],
		'NOTENOUGH'             => [
			'reason'  => '用户帐号余额不足',
			'resolve' => '用户帐号余额不足，请用户充值或更换支付卡后再支付',
		],
		'ORDERPAID'             => [
			'reason'  => '商户订单已支付，无需重复操作',
			'resolve' => '商户订单已支付，无需更多操作',
		],
		'ORDERCLOSED'           => [
			'reason'  => '当前订单已关闭，无法支付',
			'resolve' => '当前订单已关闭，请重新下单',
		],
		'SYSTEMERROR'           => [
			'reason'  => '系统超时',
			'resolve' => '系统异常，请用相同参数重新调用',
		],
		'APPID_NOT_EXIST'       => [
			'reason'  => '参数中缺少APPID',
			'resolve' => '请检查APPID是否正确',
		],
		'MCHID_NOT_EXIST'       => [
			'reason'  => '参数中缺少MCHID',
			'resolve' => '请检查MCHID是否正确',
		],
		'APPID_MCHID_NOT_MATCH' => [
			'reason'  => 'appid和mch_id不匹配',
			'resolve' => '请确认appid和mch_id是否匹配',
		],
		'LACK_PARAMS'           => [
			'reason'  => '缺少必要的请求参数',
			'resolve' => '请检查参数是否齐全',
		],
		'OUT_TRADE_NO_USED'     => [
			'reason'  => '同一笔交易不能多次提交',
			'resolve' => '请核实商户订单号是否重复提交',
		],
		'SIGNERROR'             => [
			'reason'  => '参数签名结果不正确',
			'resolve' => '请检查签名参数和方法是否都符合签名算法要求',
		],
		'XML_FORMAT_ERROR'      => [
			'reason'  => 'XML格式错误',
			'resolve' => '请检查XML参数格式是否正确',
		],
		'REQUIRE_POST_METHOD'   => [
			'reason'  => '未使用post传递参数',
			'resolve' => '请检查请求参数是否通过post方法提交',
		],
		'POST_DATA_EMPTY'       => [
			'reason'  => 'post数据不能为空',
			'resolve' => '请检查post数据是否为空',
		],
		'NOT_UTF8'              => [
			'reason'  => '未使用指定编码格式',
			'resolve' => '请使用NOT_UTF8编码格式',
		],
	];

	/**
	 * Construct the exception. Note: The message is NOT binary safe.
	 *
	 * @link https://php.net/manual/en/exception.construct.php
	 * @param string     $message [optional] The Exception message to throw.
	 * @param string     $errCode [optional] The Exception code.
	 * @param \Throwable $previous [optional] The previous throwable used for the exception chaining.
	 */
	public function __construct($message = "", $errCode = null, Throwable $previous = null){
		parent::__construct($message, 0, $previous);
		$this->errCode = $errCode;
	}

	/**
	 * 获取错误码
	 *
	 * @return string
	 */
	public function getErrCode(){
		return $this->errCode;
	}

	/**
	 * 获取错误原因
	 *
	 * @return string
	 */
	public function getReason(){
		return $this->getErrorByErrCode('reason');
	}

	/**
	 * 获取错误数据
	 *
	 * @param $type
	 * @return mixed
	 */
	private function getErrorByErrCode($type){
		if(is_null($this->errCode)) return null;
		$errCode = $this->errCode;
		return isset(self::$ERROR[$errCode]) ? self::$ERROR[$errCode][$type] : null;
	}

	/**
	 * 获取错误解决方案
	 *
	 * @return string
	 */
	public function getResole(){
		return $this->getErrorByErrCode('resolve');
	}
}
