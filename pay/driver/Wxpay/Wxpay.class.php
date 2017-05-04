<?php
namespace Pay\Wxpay;
use Pay\PayBase;
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR;
class Wxpay implements PayBase{
	const WX_APP_KEY='';
	const WX_APP_SCRIPT='';
	const WX_H5_KEY='';
	const WX_H5_SCRIPT='';
	const WX_NATIVE_KEY='';
	const WX_NATIVE_SCRIPT='';
	
	/**
	 * 获取订单详细信息
	 * (non-PHPdoc)
	 * @see PayBase::get_order_detail()
	 */
	public function get_order_detail($data){
		
		
	}
	
	/**
	 * 异步通知回调函数
	 * (non-PHPdoc)
	 * @see PayBase::notify()
	 */
	public function notify(){
		echo "this is weixin notify";
	}
	
	/**
	 * 起调支付页面
	 * (non-PHPdoc) 
	 * @see PayBase::initiate()
	 */
	public function initiate($orderdetail,$callType,$payconfig){
		
		
		
		
	}
	
}