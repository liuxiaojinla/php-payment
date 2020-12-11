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
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventDispatcherServiceProvider implements ServiceProviderInterface{
	
	/**
	 * Registers services on the given container.
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 *
	 * @param Container $pimple A container instance
	 */
	public function register(Container $pimple){
		$pimple['events'] = function($app){
			$dispatcher = new EventDispatcher();
			
			foreach($app->config->get('events.listen', []) as $event => $listeners){
				foreach($listeners as $listener){
					$dispatcher->addListener($event, $listener);
				}
			}
			
			return $dispatcher;
		};
	}
}
