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
	 * @var array
	 */
	protected $defaultConfig = [];
	
	/**
	 * @var array
	 */
	protected $userConfig = [];
	
	/**
	 * Constructor.
	 *
	 * @param array $config
	 * @param array $prepends
	 */
	public function __construct(array $config = [], array $prepends = []){
		$this->registerProviders($this->getProviders());
		
		parent::__construct($prepends);
		
		$this->userConfig = $config;
		
		$this->events->dispatch(new Events\ApplicationInitialized($this));
	}
	
	/**
	 * @return array
	 */
	public function getConfig(){
		$base = [
			// http://docs.guzzlephp.org/en/stable/request-options.html
			'http' => [
				'timeout' => 30.0,
				// 'base_uri' => 'https://api.weixin.qq.com/',
			],
			
			// 'response_type' => 'raw',
		];
		
		return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
	}
	
	/**
	 * Return all providers.
	 *
	 * @return array
	 */
	public function getProviders(){
		return array_merge([
			ConfigServiceProvider::class,
			LogServiceProvider::class,
			RequestServiceProvider::class,
			HttpClientServiceProvider::class,
			EventDispatcherServiceProvider::class,
		], $this->providers);
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
	 * @param array $providers
	 */
	public function registerProviders(array $providers){
		foreach($providers as $provider){
			parent::register(new $provider());
		}
	}
}
