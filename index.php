<?php
include 'Pay.class.php';
use Pay\Pay;
$options=array(
	 		'call_type'=>'h5',
	 		'goods_type'=>'buy',
 			'pay_type'=>'jishi'
	 );
$pay=new Pay('Alipay', $options);

$orderinfo=array(
			'ordersn'=>'201227020125',
			"notify_url"		=> 'notify_url',
			"return_url"		=> "return_url",
			"seller_email"		=> "seller_email",
			"out_trade_no"		=> "out_trade_no",
			"subject"			=> "this is a subject",
			"total_fee"			=> "100.5",
			"body"				=> "this is body",
			"show_url"			=> "this is goods show url",
);
$pay->get_order_detail($orderinfo);
echo 12;