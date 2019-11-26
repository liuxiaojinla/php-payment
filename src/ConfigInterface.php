<?php
/**
 * The following code, none of which has BUG.
 *
 * @author: BD<657306123@qq.com>
 * @date: 2019/11/26 15:09
 */

namespace xin\payment;

interface ConfigInterface{

	public function getWechatAppId();

	public function getWechatMchId();

	public function getWechatKey();
}
