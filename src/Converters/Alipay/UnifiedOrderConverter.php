<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */
namespace Xin\Payment\Converters\Alipay;

use Xin\Payment\Bus\Base\UnifiedOrderResult;
use Xin\Payment\Bus\Input;
use Xin\Payment\Contracts\Converter;

class UnifiedOrderConverter implements Converter{
	
	/**
	 * @param \Xin\Payment\Bus\Input $input
	 * @return \Xin\Payment\Bus\Input
	 */
	public function convertInput(Input $input){
		/** @var \Xin\Payment\Bus\Base\UnifiedOrderInput $input */
		return $input->withTransformKeys([
			'openid'       => 'buyer_id',
			'out_trade_no' => 'tradeNO',
		]);
	}
	
	/**
	 * @param array                  $output
	 * @param \Xin\Payment\Bus\Input $input
	 * @param array                  $config
	 * @return \Xin\Payment\Bus\Base\UnifiedOrderResult|\Xin\Payment\Bus\Result
	 */
	public function convertOutput($output, Input $input, $config = []){
		return new UnifiedOrderResult($output, $input, $config, $output);
	}
}
