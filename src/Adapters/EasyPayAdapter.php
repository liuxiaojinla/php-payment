<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */
namespace Xin\Payment\Adapters;

use  Xin\Payment\Bus\UnifiedOrderInput;
use Xin\Payment\Bus\Input;
use Xin\Payment\PayChannel;
use Xin\Payment\TradeType;
use Xin\Payment\Exceptions\BusinessException;
use Xin\Payment\Exceptions\GatewayException;
use Xin\Payment\Exceptions\InvalidConfigException;
use Xin\Payment\Exceptions\InvalidSignException;
use Yansongda\Pay\Exceptions;
use Yansongda\Pay\Pay;

class EasyPayAdapter extends AbstractAdapter{
	
	/**
	 * @var array
	 */
	protected $realCommonMethodMap = [
		'orderQuery'  => 'find',
		'refundQuery' => 'find',
		'refund'      => 'refund',
		'closeOrder'  => 'close',
	];
	
	/**
	 * @var string[]
	 */
	protected $wechatUnifiedOrderMap = [
		TradeType::NATIVE   => 'scan',
		TradeType::SCAN     => 'pos',
		TradeType::JSAPI    => 'mp',
		TradeType::MINI_APP => 'miniapp',
		TradeType::APP      => 'app',
		TradeType::WAP      => 'wap',
	];
	
	/**
	 * @var string[]
	 */
	protected $alipayUnifiedOrderMap = [
		TradeType::NATIVE   => 'scan',
		TradeType::SCAN     => 'pos',
		TradeType::JSAPI    => 'mini',
		TradeType::MINI_APP => 'mini',
		TradeType::APP      => 'app',
		TradeType::WAP      => 'wap',
	];
	
	/**
	 * EasyPayAdapter constructor.
	 *
	 * @param array $config
	 */
	public function __construct(array $config){
		// 对小程序参数做特殊处理
		if(isset($config['wechat'])){
			$wxConfig = &$config['wechat'];
			
			if(isset($wxConfig['app_id']) && !isset($wxConfig['appid'])){
				$wxConfig['appid'] = $wxConfig['app_id'];
			}
			if(isset($wxConfig['app_id']) && !isset($wxConfig['miniapp_id'])){
				$wxConfig['miniapp_id'] = $wxConfig['app_id'];
			}
			
			if(isset($wxConfig['sub_app_id']) && !isset($wxConfig['sub_appid'])){
				$wxConfig['sub_appid'] = $wxConfig['sub_app_id'];
			}
			if(isset($wxConfig['sub_app_id']) && !isset($wxConfig['sub_miniapp_id'])){
				$wxConfig['sub_miniapp_id'] = $wxConfig['sub_app_id'];
			}
			
			unset($wxConfig);
		}
		
		parent::__construct($config);
	}
	
	/**
	 * @inheritDoc
	 */
	protected function createGateway($gateway){
		$config = $this->config(strtolower($gateway));
		if(empty($config)){
			throw new InvalidConfigException("Unable to get a valid gateway configuration[$gateway]");
		}
		
		return call_user_func([
			Pay::class, $gateway,
		], $config);
	}
	
	/**
	 * 解析入口参数
	 *
	 * @param string                 $channel
	 * @param string                 $action
	 * @param \Xin\Payment\Bus\Input $input
	 * @param array                  $arguments
	 * @return mixed
	 */
	protected function parseArguments($channel, $action, Input $input, array $arguments){
		$inputArr = $input->toArray();
		
		if($input instanceof UnifiedOrderInput){
			unset($inputArr['trade_type']);
		}
		
		array_unshift($arguments, $inputArr);
		
		return $arguments;
	}
	
	/**
	 * 解析输出数据
	 *
	 * @param string $channel
	 * @param string $action
	 * @param mixed  $result
	 * @param array  $arguments
	 * @return mixed
	 */
	protected function parseOutput($channel, $action, $result, array $arguments){
		return $result->all();
	}
	
	/**
	 * @inheritDoc
	 */
	protected function realMethodName($channel, $action, Input $input){
		if('unifiedOrder' == $action){
			/** @var \ Xin\Payment\Bus\UnifiedOrderInput $input */
			if($channel === PayChannel::ALIPAY){
				$tradeType = $input->getTradeType();
				return $this->alipayUnifiedOrderMap[$tradeType];
			}else{
				$tradeType = $input->getTradeType();
				return $this->wechatUnifiedOrderMap[$tradeType];
			}
		}
		
		return parent::realMethodName($channel, $action, $input);
	}
	
	/**
	 * @param \Exception $e
	 * @return \Exception
	 */
	protected function castException(\Exception $e){
		if($e instanceof Exceptions\InvalidSignException){
			$e = new InvalidSignException($e->getMessage());
		}elseif($e instanceof Exceptions\InvalidArgumentException){
			$e = new \InvalidArgumentException($e->getMessage());
		}elseif($e instanceof Exceptions\InvalidConfigException){
			$e = new InvalidConfigException($e->getMessage());
		}elseif($e instanceof Exceptions\BusinessException){
			$e = new BusinessException(
				str_replace("ERROR_GATEWAY: ERROR_BUSINESS:", "", $e->getMessage()),
				$e->raw);
		}elseif($e instanceof Exceptions\GatewayException){
			$e = new GatewayException(
				str_replace("ERROR_GATEWAY: ", "", $e->getMessage()),
				$e->raw, $e->getCode());
		}elseif($e instanceof Exceptions\InvalidGatewayException){
			$e = new GatewayException(
				str_replace("INVALID_GATEWAY: ", "", $e->getMessage()),
				$e->raw, $e->getCode());
		}
		return $e;
	}
	
	/**
	 * 异步回调结果
	 *
	 * @param bool $needDecrypt
	 * @return array
	 */
	public function notify($needDecrypt = false){
		return $this->gateway()->verify(null, $needDecrypt);
	}
}
