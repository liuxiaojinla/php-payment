<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/3 16:10
 */

namespace xin\payment;

final class Util{

	/**
	 * 格式化参数格式化成url参数
	 *
	 * @param array $data
	 * @return string
	 */
	public static function buildParamsToUrl($data){
		$buff = "";
		foreach($data as $k => $v){
			if($k != "sign" && $v != "" && !is_array($v)){
				$buff .= $k."=".$v."&";
			}
		}
		$buff = trim($buff, "&");
		return $buff;
	}

	/**
	 * 改变数组中多个key的名称
	 *
	 * @param array $arr
	 * @param array $keysMap
	 * @return array
	 */
	public static function transformKeys(array $arr, array $keysMap){
		foreach($keysMap as $key => $newKey){
			if(!isset($arr[$key])) continue;

			$value = &$arr[$key];
			unset($arr[$key]);
			$arr[$newKey] = $value;
		}
		return $arr;
	}

	/**
	 * 驼峰转下划线
	 *
	 * @param string $value
	 * @param string $delimiter
	 * @return string
	 */
	public static function snake($value, $delimiter = '_'){
		if(!ctype_lower($value)){
			$value = preg_replace('/\s+/u', '', $value);
			$value = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1'.$delimiter, $value));
		}

		return $value;
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

	/**
	 * 生成随机字符串
	 *
	 * @param string $factor
	 * @return string
	 */
	public static function nonceStr($factor = ''){
		return md5(uniqid(md5(microtime(true).$factor), true));
	}
}
