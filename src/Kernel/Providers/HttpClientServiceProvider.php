<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/3 16:10
 */
namespace Xin\Payment\Kernel\Providers;

use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class HttpClientServiceProvider.
 */
class HttpClientServiceProvider implements ServiceProviderInterface{
	
	/**
	 * Registers services on the given container.
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 *
	 * @param Container $pimple A container instance
	 */
	public function register(Container $pimple){
		$pimple['http_client'] = function($app){
			return new Client($app['config']->get('http', []));
		};
	}
}
