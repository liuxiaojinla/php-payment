<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Bus;

use Xin\Payment\Contracts\NotifyResult as NotifyResultContract;
use Xin\Payment\PayChannel;

class NotifyResult implements NotifyResultContract{

	use Attribute;

	/**
	 * @var bool
	 */
	protected static $wechatV3 = false;

	/**
	 * @var array
	 */
	protected $raw = null;

	/**
	 * NotifyResult constructor.
	 *
	 * @param string $channel
	 * @param array  $array
	 */
	public function __construct($channel, $array){
		$this->channel = $channel;
		$this->raw = $array;

		$this->transformData();
	}

	/**
	 * 转化数据
	 */
	protected function transformData(){
		$converter = $this->resolveConverter();
		if(empty($converter)){
			$this->data = $this->raw;
		}else{
			$this->data = $converter->transform($this->raw);
		}
	}

	/**
	 * 解析转化器
	 *
	 * @return \Xin\Payment\Contracts\NotifyConverter
	 */
	protected function resolveConverter(){
		$class = get_class($this);
		$pos = strripos($class, "\\");
		$className = substr($class, $pos + 1, -6);

		$realClass = "\\Xin\\Payment\\Converters\\{$this->channel}\\{$className}NotifyConverter";
		if(!class_exists($realClass)){
			return null;
		}

		return new $realClass($this);
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
		if($this->channel === PayChannel::WECHAT){
			if($this->raw['return_code'] === 'SUCCESS'
				&& (!isset($this->raw['result_code']) || $this->raw['result_code'] === 'SUCCESS')){
				return true;
			}
		}elseif($this->channel === PayChannel::ALIPAY){
			return true;
		}

		return false;
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
			if(static::$wechatV3){
				return json_encode([
					"code"    => $isSuccess ? "SUCCESS" : "FAIL",
					"message" => $errMsg,
				]);
			}else{
				$state = $isSuccess ? 'SUCCESS' : 'FAIL';
				return "<xml><return_code><![CDATA[{$state}]]></return_code><return_msg><![CDATA[{$errMsg}]]></return_msg></xml>";
			}
		}
	}

	/**
	 * @return string
	 */
	public function getChannel(){
		return $this->channel;
	}

	/**
	 * @return array
	 */
	public function getRaw(){
		return $this->raw;
	}

	/**
	 * @param bool $wechatV3
	 */
	public static function setWechatV3($wechatV3){
		static::$wechatV3 = $wechatV3;
	}

}
