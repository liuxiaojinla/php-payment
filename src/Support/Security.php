<?php
/**
 * Talents come from diligence, and knowledge is gained by accumulation.
 *
 * @author: 晋<657306123@qq.com>
 */

namespace Xin\Payment\Kernel\Support;

class Security{
	
	/**
	 * Generate a signature.
	 *
	 * @param array  $attributes
	 * @param string $key
	 * @param string $encryptMethod
	 * @return string
	 */
	function generate_sign(array $attributes, $key, $encryptMethod = 'md5'){
		ksort($attributes);
		
		$attributes['key'] = $key;
		
		return strtoupper(call_user_func_array($encryptMethod, [urldecode(http_build_query($attributes))]));
	}
	
	/**
	 * @param string $signType
	 * @param string $secretKey
	 * @return \Closure|string
	 */
	function get_encrypt_method(string $signType, string $secretKey = ''){
		if('HMAC-SHA256' === $signType){
			return function($str) use ($secretKey){
				return hash_hmac('sha256', $str, $secretKey);
			};
		}
		
		return 'md5';
	}
	
	/**
	 * @param string $content
	 * @param string $publicKey
	 * @return string
	 */
	function rsa_public_encrypt($content, $publicKey){
		$encrypted = '';
		openssl_public_encrypt($content, $encrypted, openssl_pkey_get_public($publicKey), OPENSSL_PKCS1_OAEP_PADDING);
		
		return base64_encode($encrypted);
	}
}
