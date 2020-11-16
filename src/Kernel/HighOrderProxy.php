<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Kernel;

class HighOrderProxy{
	
	/**
	 * The target being tapped.
	 *
	 * @var mixed
	 */
	public $target;
	
	/**
	 * Create a new tap proxy instance.
	 *
	 * @param mixed $target
	 * @return void
	 */
	public function __construct($target){
		$this->target = $target;
	}
	
	/**
	 * Dynamically pass method calls to the target.
	 *
	 * @param string $method
	 * @param array  $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters){
		$this->target->{$method}(...$parameters);
		
		return $this->target;
	}
}
