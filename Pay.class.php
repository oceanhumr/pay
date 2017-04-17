<?php
namespace Pay;
use Pay\Alipay\Alipay;
use Pay\Wxpay\Wxpay;
use Pay\Unpay\Unpay;
require_once 'Alipay/Alipay.class.php';				//支付宝支付
require_once 'Wxpay/Wxpay.class.php';				//微信支付
require_once 'Unpay/Unpay.class.php';				//银联支付
class Pay{
	const PAY_TYPE=['wxpay','alipay','unpay'];			//支付类型
	const CALL_TYPE=['1'=>'app','2'=>'h5','3'=>'native'];		//起调支付控件类型
	const GOODS_TYPE=['recharge','buy'];			//购买类型（虚拟商品支付、实物商品）
	static private  $source=[];		//支付对象实例
	private $pay_type=NULL;			//当前的支付方式
	private $_instance=NULL;		//当前的支付实例
	/**
	 * 初始化
	 * @param unknown $pay_type
	 * @param unknown $options（配置文件）
	 * @return multitype:
	 */
	
	/*参数示例*/
	//###### 	 $options=array(
	//######	 		'call_type'=>'app',
	//###### 	 		'goods_type'=>'buy',
	//###### 	 		'pay_type'=>'jishi'
	//###### 	 );
	public function __construct($pay_type,$options){
			$this->pay_type=strtolower($pay_type);
			if(!isset(self::$source[md5($this->pay_type)])){
				switch ($this->pay_type){
					case 'alipay':
						self::$source[md5($this->pay_type)]=new	Alipay($options);
						break;
					case 'wxpay':
						self::$source[md5($this->pay_type)]=new Wxpay($options);
						break;
					case 'unpay':
						self::$source[md5($this->pay_type)]=new Unpay($options);
						break;
				}
		  	}
 		  	//返回对象
			$this->_instance=self::$source[md5($this->pay_type)];
			return $this->_instance;
	}
	
	
	
	
	/**
	 * 统一处理订单的详细信息
	 * @param array $order_info 	传入订单的详细信息
	 */
	public function get_order_detail($order_info){
		if(empty($order_info)){return false;}
		call_user_func_array(array($this->_instance,'get_order_detail'), $order_info);
	}
	
	/**
	 * 统一处理订单的回调
	 */
	public function notify(){
		$getNotifyData=$_REQUEST;
		return call_user_func_array(array($this->_instance,'notify'), $getNotifyData);
	}

}
