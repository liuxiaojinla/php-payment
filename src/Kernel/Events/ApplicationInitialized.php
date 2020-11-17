<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Kernel\Events;

use Xin\Payment\Kernel\ServiceContainer;

/**
 * Class ApplicationInitialized.
 */
class ApplicationInitialized{
	
	/**
	 * @var \Xin\Payment\Kernel\ServiceContainer
	 */
	public $app;
	
	/**
	 * @param \Xin\Payment\Kernel\ServiceContainer $app
	 */
	public function __construct(ServiceContainer $app){
		$this->app = $app;
	}
}
