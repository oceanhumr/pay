<?php
// +----------------------------------------------------------------------
// | LIKE [ THERE IS NO IF ]
// +----------------------------------------------------------------------
// | Author: Mr.hu <huhaiyang7788@163.com>
// +----------------------------------------------------------------------
// | DESC: 支付宝支付
// +----------------------------------------------------------------------
namespace pay\driver;

use pay\tool\Base;

class Alipay implements Base{
    /**
     * 创建标准的支付宝支付参数
     * @param $order_info
     * @ Mr.hu.
     */
    public function createStandardPayParams($order_info)
    {
        var_dump($order_info);
    }
    
}
