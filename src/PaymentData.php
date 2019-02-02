<?php
/**
 * I know no such things as genius,it is nothing but labor and diligence.
 *
 * @copyright (c) 2015~2019 BD All rights reserved.
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @author BD<657306123@qq.com>
 */
namespace xin\payment;

use xin\core\Container;

/**
 * 支付参数基类
 *
 * @package xin\payment
 */
class PaymentData extends Container{

	/**
	 * 检查参数是否存在
	 *
	 * @param array $keys
	 * @param bool  $isBatch
	 * @param bool  $isFailException
	 * @return array
	 * @throws PaymentException
	 */
	public function check($keys, $isBatch = false, $isFailException = true){
		$diff = parent::check($keys, $isFailException ? false : $isBatch);
		if(!empty($diff) && $isFailException) throw new PaymentException("缺少统一支付接口必填参数{$diff[0]}！");
		return $diff;
	}

	/**
	 * 获取支付签名
	 *
	 * @return string
	 */
	public function getSign(){
		return $this->get('sign');
	}

	/**
	 * 数据签名是否存在
	 *
	 * @return bool
	 */
	public function hasSign(){
		return $this->has('sign');
	}

	/**
	 * 输出xml字符
	 *
	 * @throws PaymentException
	 * @return string
	 **/
	public function toXml(){
		if(count($this->options) <= 0){
			throw new PaymentException("数组数据异常！");
		}

		$xml = "<xml>";
		foreach($this->options as $key => $val){
			if(is_numeric($val)){
				$xml .= "<".$key.">".$val."</".$key.">";
			}else{
				$xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
			}
		}
		$xml .= "</xml>";
		return $xml;
	}
}
