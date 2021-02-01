<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/3 16:10
 */

namespace Xin\Payment\Support;

/**
 * Class Str.
 */
class Str{
	
	/**
	 * The cache of snake-cased words.
	 *
	 * @var array
	 */
	protected static $snakeCache = [];
	
	/**
	 * The cache of camel-cased words.
	 *
	 * @var array
	 */
	protected static $camelCache = [];
	
	/**
	 * The cache of studly-cased words.
	 *
	 * @var array
	 */
	protected static $studlyCache = [];
	
	/**
	 * Convert a value to camel case.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function camel($value){
		if(isset(static::$camelCache[$value])){
			return static::$camelCache[$value];
		}
		
		return static::$camelCache[$value] = lcfirst(static::studly($value));
	}
	
	/**
	 * Generate a more truly "random" alpha-numeric string.
	 *
	 * @param int $length
	 * @return string
	 */
	public static function random($length = 16){
		$string = '';
		
		while(($len = strlen($string)) < $length){
			$size = $length - $len;
			
			$bytes = static::randomBytes($size);
			
			$string .= substr(str_replace(['/', '+', '='], '', base64_encode($bytes)), 0, $size);
		}
		
		return $string;
	}
	
	/**
	 * Generate a more truly "random" bytes.
	 *
	 * @param int $length
	 * @return string
	 * @codeCoverageIgnore
	 * @noinspection PhpDocMissingThrowsInspection
	 */
	public static function randomBytes($length = 16){
		if(function_exists('random_bytes')){
			$bytes = random_bytes($length);
		}elseif(function_exists('openssl_random_pseudo_bytes')){
			$bytes = openssl_random_pseudo_bytes($length, $strong);
			if(false === $bytes || false === $strong){
				throw new \RuntimeException('Unable to generate random string.');
			}
		}else{
			throw new \RuntimeException('OpenSSL extension is required for PHP 5 users.');
		}
		
		return $bytes;
	}
	
	/**
	 * Generate a "random" alpha-numeric string.
	 * Should not be considered sufficient for cryptography, etc.
	 *
	 * @param int $length
	 * @return string
	 */
	public static function quickRandom($length = 16){
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
	}
	
	/**
	 * 生成随机字符串
	 *
	 * @param string $factor
	 * @return string
	 */
	public static function nonceStr($factor = ''){
		return md5(uniqid(md5(microtime(true).$factor), true));
	}
	
	/**
	 * Convert the given string to upper-case.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function upper($value){
		return mb_strtoupper($value);
	}
	
	/**
	 * Convert the given string to title case.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function title($value){
		return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
	}
	
	/**
	 * Convert a string to snake case.
	 *
	 * @param string $value
	 * @param string $delimiter
	 * @return string
	 */
	public static function snake($value, $delimiter = '_'){
		$key = $value.$delimiter;
		
		if(isset(static::$snakeCache[$key])){
			return static::$snakeCache[$key];
		}
		
		if(!ctype_lower($value)){
			$value = strtolower(preg_replace('/(.)(?=[A-Z])/', '$1'.$delimiter, $value));
		}
		
		return static::$snakeCache[$key] = trim($value, '_');
	}
	
	/**
	 * Convert a value to studly caps case.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function studly($value){
		$key = $value;
		
		if(isset(static::$studlyCache[$key])){
			return static::$studlyCache[$key];
		}
		
		$value = ucwords(str_replace(['-', '_'], ' ', $value));
		
		return static::$studlyCache[$key] = str_replace(' ', '', $value);
	}
	
	/**
	 * Get the class "basename" of the given object / class.
	 *
	 * @param string|object $class
	 * @return string
	 */
	public static function classBasename($class){
		$class = is_object($class) ? get_class($class) : $class;
		
		return basename(str_replace('\\', '/', $class));
	}
	
	/**
	 * 获取毫秒级别的时间戳
	 */
	public static function getMillisecond(){
		$time = explode(" ", microtime());
		$time = $time[1].($time[0] * 1000);
		$time2 = explode(".", $time);
		$time = $time2[0];
		return $time;
	}
}
