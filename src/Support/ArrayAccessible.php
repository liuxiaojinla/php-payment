<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<liuxingwu@duoguan.com>
 * @date: 2019/6/3 16:10
 */

namespace Xin\Payment\Kernel\Support;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Xin\Payment\Kernel\Contracts\Arrayable;

/**
 * Class ArrayAccessible.
 */
class ArrayAccessible implements ArrayAccess, IteratorAggregate, Arrayable{
	
	private $array;
	
	public function __construct(array $array = []){
		$this->array = $array;
	}
	
	public function offsetExists($offset){
		return array_key_exists($offset, $this->array);
	}
	
	public function offsetGet($offset){
		return $this->array[$offset];
	}
	
	public function offsetSet($offset, $value){
		if(null === $offset){
			$this->array[] = $value;
		}else{
			$this->array[$offset] = $value;
		}
	}
	
	public function offsetUnset($offset){
		unset($this->array[$offset]);
	}
	
	public function getIterator(){
		return new ArrayIterator($this->array);
	}
	
	public function toArray(){
		return $this->array;
	}
}
