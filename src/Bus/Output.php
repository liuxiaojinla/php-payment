<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace Xin\Payment\Bus;

use Psr\Http\Message\ResponseInterface;

/**
 * 请求结果
 * @method string getAppid()
 * @method bool hasAppid()
 * @method string getMchId()
 * @method bool hasMchId()
 */
abstract class Output extends Attribute{
	
	/**
	 * @var \Xin\Payment\Bus\Input
	 */
	protected $input;
	
	/**
	 * @var array
	 */
	protected $config = null;
	
	/**
	 * @var mixed
	 */
	protected $response;
	
	/**
	 * PaymentResult constructor.
	 *
	 * @param array      $data
	 * @param Input|null $input
	 * @param mixed      $config
	 * @param mixed      $response
	 */
	public function __construct(array $data = [], Input $input = null, $config = [], $response = null){
		parent::__construct($data);
		
		$this->input = $input;
		$this->channel = $input->getChannel();
		$this->config = $config;
		$this->response = $response;
	}
	
	/**
	 * 获取配置
	 *
	 * @return \Xin\Payment\Kernel\Config
	 */
	public function getConfig(){
		return $this->config;
	}
	
	/**
	 * @return \Xin\Payment\Bus\Input
	 */
	public function getInput(){
		return $this->input;
	}
	
	/**
	 * @return mixed
	 */
	public function getResponse(){
		return $this->response;
	}
	
	/**
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value){
		throw new \RuntimeException(
			"Assignment to {$offset} is not allowed"
		);
	}
	
	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset){
		throw new \RuntimeException(
			"Assignment to {$offset} is not allowed"
		);
	}
	
	/**
	 * 使用xml字符串构建
	 *
	 * @param string                      $xml
	 * @param \Xin\Payment\Bus\Input|null $input
	 * @param array                       $config
	 * @return static
	 */
	public static function fromXML($xml, Input $input = null, $config = []){
		if(!$xml){
			throw new \InvalidArgumentException("xml数据异常！");
		}
		
		//将XML转为array
		//禁止引用外部xml实体
		libxml_disable_entity_loader(true);
		$data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		
		return self::make($data, $input, $config, $xml);
	}
	
	/**
	 * @param \Psr\Http\Message\ResponseInterface $response
	 * @param \Xin\Payment\Bus\Input|null         $input
	 * @param array                               $config
	 * @return static
	 */
	public static function formResponse(ResponseInterface $response, Input $input = null, $config = []){
		return self::make(
			json_decode($response->getBody()->getContents(), true),
			$input, $config, $response
		);
	}
	
	/**
	 * 生成输出类
	 *
	 * @param array                  $data
	 * @param \Xin\Payment\Bus\Input $input
	 * @param array                  $config
	 * @param null                   $response
	 * @return false|mixed
	 */
	public static function make($data, Input $input, $config = [], $response = null){
		$outputClass = substr(get_class($input), 0, -5)."Output";
		if(!class_exists($outputClass)){
			return $data;
		}
		
		if(method_exists($outputClass, '__make')){
			return call_user_func([$outputClass, '__make'], $data, $input, $config, $response);
		}
		
		return new $outputClass($data, $input, $config, $response);
	}
}
