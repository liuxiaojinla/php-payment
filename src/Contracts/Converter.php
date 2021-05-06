<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Contracts;

use Xin\Payment\Bus\Input;
use Xin\Payment\Bus\Result;

interface Converter{

	/**
	 * 转换输入参数
	 *
	 * @param Input $input
	 * @return Input
	 */
	public function convertInput(Input $input);

	/**
	 * 转换输出参数
	 *
	 * @param array $result
	 * @param Input $input
	 * @param array $config
	 * @return Result
	 */
	public function convertResult($result, Input $input, $config = []);
}
