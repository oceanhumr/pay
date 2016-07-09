<?php
//构造要请求的参数数组，无需改动
namespace Pay\Alipay\jishi\h5;
class Alipayapi{
	public static  function getDetail($orderinfo,$payconfig){
// 		return $payconfig;
		return $orderinfo;
		$orderdetail=array(
				"service" 			=> "create_direct_pay_by_user",
				"partner" 			=> $payconfig['partner'],
				"payment_type"		=> $payconfig['payment_type'],
				"notify_url"		=> $orderinfo['notify_url'],
				"return_url"		=> $orderinfo['return_url'],
				"seller_email"		=> $payconfig['seller_email'],
				"out_trade_no"		=> $orderinfo['ordersn'],
				"subject"			=> $orderinfo['subject'],
				"total_fee"			=> $orderinfo['total_fee'],
				"body"				=> $orderinfo['body'],
				"show_url"			=> $orderinfo['show_url'],
				"anti_phishing_key"	=> "",
				"exter_invoke_ip"	=> $_SERVER["REMOTE_ADDR"],
				"_input_charset"	=> $payconfig['input_charset']
		);
		return $orderdetail;
	}
}
/* $parameter = array(
				"service" 			=> "create_direct_pay_by_user",
				"partner" 			=> $payconfig['partner'],
				"payment_type"		=>$payconfig['pay_type'],
				"notify_url"		=> $orderinfo['notify_url'],
				"return_url"		=> $orderinfo['return_url'],
				"seller_email"		=> $payconfig['seller_email'],
				"out_trade_no"		=> $orderinfo['ordersn'],
				"subject"			=> $orderinfo['subject'],
				"total_fee"			=> $orderinfo['total_fee'],
				"body"				=> $orderinfo['body'],
				"show_url"			=> $orderinfo['show_url'],
				"anti_phishing_key"	=> "",
				"exter_invoke_ip"	=> $_SERVER["REMOTE_ADDR"],
				"_input_charset"	=> $payconfig['input_charset']
		);
return $parameter;
 */

//建立请求
//过滤配置信息
/* $alipay_config['partner']		= $payconfig['partner'];
$alipay_config['key']			= $payconfig['key'];
$alipay_config['sign_type']    	= $payconfig['sign_type'];
$alipay_config['input_charset']	= $payconfig['input_charset'];
$alipay_config['cacert']    	= $payconfig['cacert'];
$alipay_config['transport']    	= $payconfig['transport'];
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
echo $html_text; */
?>
