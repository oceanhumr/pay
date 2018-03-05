<?php

namespace ocean\pay;


/**
 * 支付服务驱动
 */
interface PayInterface
{

    /**
     * 获取驱动参数
     */
    public function getDriverParam();
    
    /**
     * 获取基本信息
     */
    public function driverInfo();
    
    /**
     * 配置信息
     */
    public function config();
    
    /**
     * 支付通知
     */
    public function notify();
    
    /**
     * 获取订单号
     */
    public function getOrderSn();
    
    /**
     * 支付
     */
    public function pay($order);
}
