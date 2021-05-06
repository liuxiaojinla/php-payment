<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Kernel\Support;

class Server{

	/**
	 * Get client ip.
	 *
	 * @return string
	 */
	public static function getClientIP(){
		if(!empty($_SERVER['REMOTE_ADDR'])){
			$ip = $_SERVER['REMOTE_ADDR'];
		}else{
			// for php-cli(phpunit etc.)
			$ip = defined('PHPUNIT_RUNNING') ? '127.0.0.1' : gethostbyname(gethostname());
		}

		return filter_var($ip, FILTER_VALIDATE_IP) ?: '127.0.0.1';
	}

	/**
	 * Get current server ip.
	 *
	 * @return string
	 */
	public static function getServerIP(){
		if(!empty($_SERVER['SERVER_ADDR'])){
			$ip = $_SERVER['SERVER_ADDR'];
		}elseif(!empty($_SERVER['SERVER_NAME'])){
			$ip = gethostbyname($_SERVER['SERVER_NAME']);
		}else{
			// for php-cli(phpunit etc.)
			$ip = defined('PHPUNIT_RUNNING') ? '127.0.0.1' : gethostbyname(gethostname());
		}

		return filter_var($ip, FILTER_VALIDATE_IP) ?: '127.0.0.1';
	}

	/**
	 * Return current url.
	 *
	 * @return string
	 */
	public static function getCurrentUrl(){
		$protocol = 'http://';

		if((!empty($_SERVER['HTTPS']) && 'off' !== $_SERVER['HTTPS']) || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'http') === 'https'){
			$protocol = 'https://';
		}

		return $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
}
