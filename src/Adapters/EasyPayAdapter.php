<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */
namespace Xin\Payment\Adapters;

use Xin\Payment\Bus\Base\RefundQueryInput;
use Xin\Payment\Bus\Base\UnifiedOrderInput;
use Xin\Payment\Bus\Input;
use Xin\Payment\Bus\PayChannel;
use Xin\Payment\Bus\TradeType;
use Xin\Payment\Bus\TransfersQueryInput;
use Xin\Payment\Exceptions\GatewayException;
use Xin\Payment\Exceptions\InvalidConfigException;
use Yansongda\Pay\Exceptions;
use Yansongda\Pay\Pay;
use Yansongda\Supports\Collection;

class EasyPayAdapter extends AbstractAdapter{
	
	/**
	 * @var string[]
	 */
	protected $unifiedOrderMap = [
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
	protected $alipayMap = [
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
	protected $commonMap = [
		'orderQuery'  => 'find',
		'refundQuery' => 'find',
		'refund'      => 'refund',
		'closeOrder'  => 'close',
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
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	protected function parseInput($name, array $arguments){
		$input = isset($arguments[0]) ? $arguments[0] : null;
		if($input instanceof Input){
			$arguments[0] = $input->toArray();
			
			if($input instanceof UnifiedOrderInput){
				unset($arguments[0]['trade_type']);
			}elseif($input instanceof RefundQueryInput){
				$arguments[1] = 'refund';
			}elseif($input instanceof TransfersQueryInput){
				$arguments[1] = 'transfer';
			}
		}
		
		return $arguments;
	}
	
	/**
	 * 解析输出数据
	 *
	 * @param string           $name
	 * @param Collection|mixed $result
	 * @param array            $arguments
	 * @return mixed
	 */
	protected function parseOutput($name, $result, array $arguments){
		return $result->all();
	}
	
	/**
	 * @param string                 $name
	 * @param \Xin\Payment\Bus\Input $input
	 * @return mixed|string|void
	 */
	protected function realMethodName($name, $input){
		$channel = $input->getChannel();
		
		if('unifiedOrder' == $name){
			/** @var \Xin\Payment\Bus\Base\UnifiedOrderInput $input */
			if($channel === PayChannel::ALIPAY){
			}else{
				$tradeType = $input->getTradeType();
				return $this->unifiedOrderMap[$tradeType];
			}
		}
		
		if(isset($this->commonMap[$name])){
			return $this->commonMap[$name];
		}
		
		return $name;
	}
	
	/**
	 * @param \Exception $e
	 * @return \Exception
	 */
	protected function castException(\Exception $e){
		if($e instanceof Exceptions\GatewayException){
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
	
}
