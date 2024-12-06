<?php

namespace Xin\Payment\Laravel;

use Xin\Payment\PayLog;
use Xin\Payment\PayLogContract;

class PayOrderProviderProvider implements PayLogContract
{

	/**
	 * @inerhitDoc
	 */
	public function transactionIdToOutTradeNo($transactionId, $type = null)
	{
		return PayLog::where([
			'transaction_id' => $transactionId,
		])->value('out_trade_no');
	}

	/**
	 * @inerhitDoc
	 */
	public function retrieveByOutTradeNo($outTradeNo, $type = null)
	{
		return PayLog::where([
			'out_trade_no' => $outTradeNo,
		])->find();
	}

	/**
	 * @inerhitDoc
	 */
	public function retrieveByTransactionId($transactionId, $type = null)
	{
		return PayLog::where([
			'transaction_id' => $transactionId,
		])->find();
	}

	/**
	 * @inerhitDoc
	 */
	public function retrieveByPayTradeNo($payTradeNo, $type = null)
	{
		return PayLog::where([
			'pay_trade_no' => $payTradeNo,
		])->find();
	}
}
