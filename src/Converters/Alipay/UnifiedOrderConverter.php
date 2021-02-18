<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */
namespace Xin\Payment\Converters\Alipay;

use  Xin\Payment\Bus\UnifiedOrderResult;
use Xin\Payment\Bus\Input;
use Xin\Payment\Bus\Result;
use Xin\Payment\Contracts\Converter;

class UnifiedOrderConverter implements Converter{
	
	/**
	 * @param Input $input
	 * @return Input
	 */
	public function convertInput(Input $input){
		return $input->withTransformKeys([
			'openid'       => 'buyer_id',
			'out_trade_no' => 'tradeNO',
		]);
	}
	
	/**
	 * @param array $result
	 * @param Input $input
	 * @param array $config
	 * @return UnifiedOrderResult|Result
	 */
	public function convertResult($result, Input $input, $config = []){
		return new UnifiedOrderResult($result, $input, $config, $result);
	}
}
