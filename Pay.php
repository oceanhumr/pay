<?php
// +----------------------------------------------------------------------
// | LIKE [ THERE IS NO IF ]
// +----------------------------------------------------------------------
// | Author: Mr.hu <huhaiyang7788@163.com>
// +----------------------------------------------------------------------
// | DESC: 支付网关
// +----------------------------------------------------------------------
require  str_replace('\\',DIRECTORY_SEPARATOR, __DIR__ . '/pay/tool/Loader.php');
class Pay{
    //参数配置
    private $config;
    private $pay_type;
    private $driver;
    private $call_type;

    /**
     * Pay constructor.
     * @param null $config 初始化的配置文件
     */
    public function __construct($config=null)
    {
        //注册自动加载类
        \pay\tool\Loader::register();

        //加载配置参数
        $this->config=$config;

        //设置支付方式,默认是支付宝支付
        $this->pay_type=isset($config['pay_type'])?:'alipay';

        //设置支付页面的起吊方式，默认是网页支付
        $this->call_type=isset($config['call_type'])?:'h5';
        
        //创建对应的驱动
        $this->driver=\pay\tool\Factory::getDriver($this->pay_type);
    }


    /**
     * 根据不同的支付方式生成对应的支付参数
     * @param $order_info
     * @ Mr.hu.
     */
    public function createStandardPayParams($order_info)
    {
        $this->driver->createStandardPayParams($order_info);
    }



}

$pay=new Pay();
$pay->createStandardPayParams('测试数据');

