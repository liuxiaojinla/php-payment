<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Kernel;

use Pimple\Container;
use Xin\Payment\Kernel\Providers\ConfigServiceProvider;
use Xin\Payment\Kernel\Providers\EventDispatcherServiceProvider;
use Xin\Payment\Kernel\Providers\HttpClientServiceProvider;
use Xin\Payment\Kernel\Providers\LogServiceProvider;
use Xin\Payment\Kernel\Providers\RequestServiceProvider;

/**
 * Class ServiceContainer.
 *
 * @property \Xin\Payment\Kernel\Config                         $config
 * @property \Symfony\Component\HttpFoundation\Request          $request
 * @property \GuzzleHttp\Client                                 $http_client
 * @property \Monolog\Logger                                    $logger
 * @property \Symfony\Component\EventDispatcher\EventDispatcher $events
 */
class ServiceContainer extends Container{
	
	/**
	 * @var array
	 */
	protected $providers = [];
	
	/**
	 * Constructor.
	 *
	 * @param array $config
	 * @param array $prepends
	 */
	public function __construct(array $config = [], array $prepends = []){
		$this['config'] = new Config(array_replace_recursive([
			// http://docs.guzzlephp.org/en/stable/request-options.html
			'http' => [
				'timeout' => 15.0,
				// 'base_uri' => 'https://api.weixin.qq.com/',
			],
			
			// 'response_type' => 'raw',
		], $config));
		
		$this->registerProviders($this->getProviders());
		
		parent::__construct($prepends);
		
		$this->events->dispatch(new Events\ApplicationInitialized($this));
	}
	
	/**
	 * @param string $id
	 * @param mixed  $value
	 */
	public function rebind($id, $value){
		$this->offsetUnset($id);
		$this->offsetSet($id, $value);
	}
	
	/**
	 * Magic get access.
	 *
	 * @param string $id
	 * @return mixed
	 */
	public function __get($id){
		return $this->offsetGet($id);
	}
	
	/**
	 * Magic set access.
	 *
	 * @param string $id
	 * @param mixed  $value
	 */
	public function __set($id, $value){
		$this->offsetSet($id, $value);
	}
	
	/**
	 * Return all providers.
	 *
	 * @return array
	 */
	protected function getProviders(){
		return array_merge([
			LogServiceProvider::class,
			RequestServiceProvider::class,
			HttpClientServiceProvider::class,
			EventDispatcherServiceProvider::class,
		], $this->providers);
	}
	
	/**
	 * @param array $providers
	 */
	protected function registerProviders(array $providers){
		foreach($providers as $provider){
			parent::register(new $provider());
		}
	}
}
