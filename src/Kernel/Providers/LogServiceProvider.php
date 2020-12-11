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
use Xin\Payment\Kernel\Log\LogManager;

class LogServiceProvider implements ServiceProviderInterface{
	
	/**
	 * Registers services on the given container.
	 * This method should only be used to configure services and parameters.
	 * It should not get services.
	 *
	 * @param Container $pimple A container instance
	 */
	public function register(Container $pimple){
		$pimple['logger'] = $pimple['log'] = function($app){
			$config = $this->formatLogConfig($app);
			
			if(!empty($config)){
				$app->rebind('config', $app['config']->merge($config));
			}
			
			return new LogManager($app);
		};
	}
	
	public function formatLogConfig($app){
		if(!empty($app['config']->get('log.channels'))){
			return $app['config']->get('log');
		}
		
		if(empty($app['config']->get('log'))){
			return [
				'log' => [
					'default'  => 'errorlog',
					'channels' => [
						'errorlog' => [
							'driver' => 'errorlog',
							'level'  => 'debug',
						],
					],
				],
			];
		}
		
		return [
			'log' => [
				'default'  => 'single',
				'channels' => [
					'single' => [
						'driver' => 'single',
						'path'   => $app['config']->get('log.file') ?: \sys_get_temp_dir().'/logs/payment.log',
						'level'  => $app['config']->get('log.level', 'debug'),
					],
				],
			],
		];
	}
}
