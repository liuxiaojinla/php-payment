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
use Xin\Payment\Support\XML;

/**
 * 请求结果
 * @method string getAppid()
 * @method bool hasAppid()
 * @method string getMchId()
 * @method bool hasMchId()
 */
abstract class Result implements \ArrayAccess, \IteratorAggregate, \JsonSerializable{

	use Attribute;

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
	protected $raw;

	/**
	 * PaymentResult constructor.
	 *
	 * @param array      $data
	 * @param Input|null $input
	 * @param mixed      $config
	 * @param mixed      $raw
	 */
	public function __construct(array $data = [], Input $input = null, $config = [], $raw = null){
		$this->data = $data;
		$this->input = $input;
		$this->channel = $input->getChannel();
		$this->config = $config;
		$this->raw = $raw;
	}

	/**
	 * 获取配置
	 *
	 * @return \Xin\Payment\Config
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
	public function getRaw(){
		return $this->raw;
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
	 * @param \Xin\Payment\Bus\Input|null $input
	 * @param string                      $xml
	 * @param array                       $config
	 * @return static
	 */
	public static function fromXML(Input $input, $xml, $config = []){
		if(!$xml){
			throw new \InvalidArgumentException("xml数据异常！");
		}

		$data = XML::parse($xml);

		return self::make($input, $data, $config, $xml);
	}

	/**
	 * @param \Xin\Payment\Bus\Input|null         $input
	 * @param \Psr\Http\Message\ResponseInterface $response
	 * @param array                               $config
	 * @return static
	 */
	public static function formResponse(Input $input, ResponseInterface $response, $config = []){
		return self::make(
			$input,
			json_decode($response->getBody()->getContents(), true),
			$config,
			$response
		);
	}

	/**
	 * 生成输出类
	 *
	 * @param \Xin\Payment\Bus\Input $input
	 * @param array                  $data
	 * @param array                  $config
	 * @param mixed                  $raw
	 * @return mixed
	 */
	public static function make(Input $input, $data, $config = [], $raw = null){
		$outputClass = substr(get_class($input), 0, -5)."Result";
		if(!class_exists($outputClass)){
			return $data;
		}

		if(method_exists($outputClass, '__make')){
			return call_user_func([$outputClass, '__make'], $data, $input, $config, $raw);
		}

		return new $outputClass($data, $input, $config, $raw);
	}
}
