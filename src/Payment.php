<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment;

use Psr\Http\Message\ResponseInterface;
use Xin\Payment\Bus\Input;
use Xin\Payment\Bus\NotifyResult;
use Xin\Payment\Bus\RefundNotify;
use Xin\Payment\Bus\Result;
use Xin\Payment\Bus\UnifiedOrderNotify;
use Xin\Payment\Support\Arr;
use Xin\Payment\Support\Str;

/**
 * Class Factory.
 * @method static $this makeEasyPay(array $config)
 * @method \Xin\Payment\Bus\UnifiedOrderResult unifiedOrder(\ Xin\Payment\Bus\UnifiedOrderInput $input)
 * @method \Xin\Payment\Bus\OrderQueryResult orderQuery(\ Xin\Payment\Bus\OrderQueryInput $input)
 * @method \Xin\Payment\Bus\RefundResult refund(\ Xin\Payment\Bus\RefundInput $input)
 * @method \Xin\Payment\Bus\RefundQueryResult refundQuery(\ Xin\Payment\Bus\RefundQueryInput $input)
 * @method \Xin\Payment\Bus\CloseOrderResult closeOrder(\ Xin\Payment\Bus\CloseOrderInput $input)
 * @method \Xin\Payment\Bus\ReverseResult reverse(\ Xin\Payment\Bus\ReverseInput $input)
 * @method \Xin\Payment\Bus\Transfer\TransfersResult transfer(\Xin\Payment\Bus\Transfer\TransfersResult $input)
 * @method \Xin\Payment\Bus\Transfer\TransfersQueryResult transferQuery(\Xin\Payment\Bus\Transfer\TransfersQueryResult $input)
 */
class Payment{

	/**
	 * @var array
	 */
	protected $config = [
		'adapter' => 'EasyPay',

		// 微信支付渠道
		'wechat'  => [
			'app_id'     => '',
			'mch_id'     => '',
			'key'        => '',
			'cert_path'  => '',
			'key_path'   => '',
			'notify_url' => '',
			'log'        => [
				// optional
				'file'     => './logs/wxpay.log',
				'level'    => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
				'type'     => 'daily', // optional, 可选 daily.
				'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
			],
		],

		// 支付宝支付渠道
		'alipay'  => [
			'app_id'         => '',
			'ali_public_key' => '',
			// 加密方式： **RSA2**
			'private_key'    => '',
			'log'            => [
				// optional
				'file'     => './logs/alipay.log',
				'level'    => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
				'type'     => 'daily', // optional, 可选 daily.
				'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
			],
		],
	];

	/**
	 * @var \Xin\Payment\Adapters\AbstractAdapter[]
	 */
	protected $adapters = [];

	/**
	 * @var array
	 */
	protected $converters = [];

	/**
	 * Factory constructor.
	 *
	 * @param array $config
	 */
	public function __construct(array $config){
		$this->config = array_replace_recursive($this->config, $config);
	}

	/**
	 * 获取默认的适配器
	 *
	 * @return string
	 */
	protected function getDefaultAdapter(){
		return $this->config('adapter');
	}

	/**
	 * 获取适配器实例
	 *
	 * @return \Xin\Payment\Adapters\AbstractAdapter
	 */
	protected function adapter(){
		$name = $this->getDefaultAdapter();
		return $this->resolveAdapter($name);
	}

	/**
	 * 解析适配器
	 *
	 * @param string $name
	 * @return \Xin\Payment\Adapters\AbstractAdapter
	 */
	protected function resolveAdapter($name){
		if(!isset($this->adapters[$name])){
			$this->adapters[$name] = $this->createAdapter($name);
		}

		return $this->adapters[$name];
	}

	/**
	 * 创建适配器
	 *
	 * @param string $name
	 * @return \Xin\Payment\Adapters\AbstractAdapter
	 */
	protected function createAdapter($name){
		$className = Str::studly($name);
		$realClass = "\\Xin\\Payment\\Adapters\\{$className}Adapter";
		return new $realClass($this->config);
	}

	/**
	 * 获取配置信息
	 *
	 * @param string $key
	 * @param mixed  $default
	 * @return array|mixed|null
	 */
	public function config($key, $default = null){
		return Arr::get($this->config, $key, $default);
	}

	/**
	 * 获取异步回调结果
	 *
	 * @param string $channel
	 * @return \Xin\Payment\Bus\NotifyResult
	 */
	public function notify($channel = null){
		return new NotifyResult(
			$channel,
			$this->adapter()->notify($channel)
		);
	}

	/**
	 * 获取统一下单异步回调结果
	 *
	 * @param string $channel
	 * @return \ Xin\Payment\Bus\UnifiedOrderNotify
	 */
	public function unifiedOrderNotify($channel = null){
		return new UnifiedOrderNotify(
			$channel,
			$this->adapter()->notify($channel)
		);
	}

	/**
	 * 获取退款异步回调结果
	 *
	 * @param string $channel
	 * @return \ Xin\Payment\Bus\RefundNotify
	 */
	public function refundNotify($channel = null){
		return new RefundNotify(
			$channel,
			$this->adapter()->notify(true)
		);
	}

	/**
	 * 解析转换器
	 *
	 * @param string $name
	 * @param string $channel
	 * @return \Xin\Payment\Contracts\Converter
	 */
	protected function resolveConverter($name, $channel){
		if(!isset($this->converters[$channel])){
			$this->converters[$channel] = [];
		}

		if(!isset($this->converters[$channel][$name])){
			$this->converters[$channel][$name] = $this->createConverter($channel, $name);
		}

		return $this->converters[$channel][$name];
	}

	/**
	 * 解析转换器
	 *
	 * @param string $channel
	 * @param string $name
	 * @return \Xin\Payment\Contracts\Converter
	 */
	protected function createConverter($channel, $name){
		$channelPath = Str::studly($channel);
		$className = Str::studly($name);

		$realClass = "\\Xin\\Payment\\Converters\\{$channelPath}\\{$className}Converter";
		if(!class_exists($realClass)){
			return null;
		}

		return new $realClass($this);
	}

	/**
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments){
		/** @var \Xin\Payment\Bus\Input $input */
		$input = isset($arguments[0]) ? $arguments[0] : null;
		if(!$input instanceof Input){
			throw new \RuntimeException("call function first arguments must of [\Xin\Payment\Bus\Input] type.");
		}

		$channel = $input->getChannel();
		$converter = $this->resolveConverter($channel, $name);
		if($converter){
			$arguments[0] = $converter->convertInput($input);
		}

		array_unshift($arguments, $name);
		$result = call_user_func_array([
			$this->adapter(), 'execute',
		], $arguments);

		if($converter){
			$output = $converter->convertResult($result, $input, $this->config);
		}else{
			if($result instanceof ResponseInterface){
				$output = Result::formResponse($input, $result, $this->config);
			}else{
				$output = Result::make($input, $result, $this->config);
			}
		}

		return $output;
	}

}
