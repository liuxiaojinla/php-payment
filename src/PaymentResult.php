<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */

namespace xin\payment;

/**
 * 请求结果
 *
 * @package xin\payment\data
 */
class PaymentResult extends PaymentData{

	/**
	 * 使用xml字符串构建一个PaymentResult对象
	 *
	 * @param string $xml
	 * @return static
	 * @throws PaymentException
	 */
	public static function fromXML($xml){
		if(!$xml) throw new PaymentException("xml数据异常！");

		//将XML转为array
		//禁止引用外部xml实体
		libxml_disable_entity_loader(true);
		$data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		return new static($data);
	}

	/**
	 * 使用stdClass实例构建一个PaymentResult对象
	 *
	 * @param \stdClass $stdClass
	 * @return static
	 */
	public static function formStdClass($stdClass){
		return new static((array)$stdClass);
	}

}
