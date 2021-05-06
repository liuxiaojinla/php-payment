<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Bus;

use Xin\Payment\Contracts\NotifyResult as NotifyResultContract;
use Xin\Payment\PayChannel;
use Xin\Payment\Support\Collection;

class NotifyResult extends Collection implements NotifyResultContract{

	/**
	 * @var string
	 */
	protected $channel;

	/**
	 * NotifyResult constructor.
	 *
	 * @param string $channel
	 * @param array  $array
	 */
	public function __construct($channel, $array){
		parent::__construct($array);
		$this->channel = $channel;
	}

	/**
	 * @inheritDoc
	 */
	public function replySuccess(){
		return self::result($this->channel);
	}

	/**
	 * @inheritDoc
	 */
	public function replyError($errMsg){
		return self::result($this->channel, $errMsg);
	}

	/**
	 * @inheritDoc
	 */
	public function isOk(){
	}

	/**
	 * 异步支付渠道商的回调结果
	 *
	 * @param string $channel
	 * @param mixed  $errMsg
	 * @return string
	 */
	public static function result($channel, $errMsg = null){
		$isSuccess = empty($errMsg);
		$errMsg = is_array($errMsg) || is_object($errMsg) ? json_encode($errMsg, JSON_UNESCAPED_UNICODE) : $errMsg;
		if(PayChannel::ALIPAY == $channel){
			return $errMsg ? $errMsg : '';
		}else{
			$state = $isSuccess ? 'SUCCESS' : 'FAIL';
			return "<xml><return_code><![CDATA[{$state}]]></return_code><return_msg><![CDATA[{$errMsg}]]></return_msg></xml>";
		}
	}
}
