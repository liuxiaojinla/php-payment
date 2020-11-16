<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/3 16:10
 */
namespace Xin\Payment\Kernel\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Xin\Payment\Kernel\Config;

/**
 * Class ConfigServiceProvider.
 */
class ConfigServiceProvider implements ServiceProviderInterface{
	
	/**
	 * Registers services on the given container.
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 *
	 * @param Container $pimple A container instance
	 */
	public function register(Container $pimple){
		$pimple['config'] = function($app){
			return new Config($app->getConfig());
		};
	}
}
