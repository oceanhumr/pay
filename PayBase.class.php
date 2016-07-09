<?php
/**
 * 支付基础
 * @author Administrator
 *
 */
namespace Pay;
interface PayBase {
	/** 获取订单详细信息
	 * @param array $data 订单信息
	 * @param string $payType 支付类型（wxpay微信支付、alipay支付宝支付、unpay银联支付）
	 * @param string $callType 调起支付页面的类型（app、h5、native）
	 * @param string $serviceType 服务类型（recharge充值【虚拟】、bay购买【实体】）
	 */
	public function get_order_detail($data);
	
	/**
	 * 异步回调通知
	 */
	public function notify();
	
	
	/**
	 * 起调支付页面
	 * @param string $payType 支付类型（wx微信支付、ali支付宝支付、unpay银联支付）
	 * @param string $callType 调起支付页面的类型（app、h5、native）
	 * @param string $serviceType服务类型（recharge充值【虚拟】、bay购买【实体】）
	 * @orderinfo array 订单的详细信息
	 */
	public function initiate($orderdetail,$callType,$payconfig);
}