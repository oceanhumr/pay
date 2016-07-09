<?php
namespace Pay\Alipay;
use Pay\PayBase;
use Pay\Alipay\jishi\h5\Alipayapi;
require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'PayBase.class.php';
class Alipay implements PayBase{
// 	###############################
	const AI_APP_KEY='';  			//app的key
	const AI_APP_SCRIPT='';			//app的script
// 	###############################
	const AI_H5_KEY='';				//h5起调的key
	const AI_H5_SCRIPT='';			//h5的script
// 	###############################
	const AI_NATIVE_KEY='';			//扫码的key
	const AI_NATIVE_SCRIPT='';		//扫码的script
// 	###############################
	protected $orderdetail=[];		//订单的详细信息
	private $alipayType='jishi';	//默认是即时到帐（jishi,danbo）
	private $callType='h5';			//起调控件类型
	private $goodsType='buy';		//商品类型(recharge虚拟,buy真实)
	
	/**
	 * 配置参数
	 * @param unknown $options
	 */
	public function __construct($options){
		if(!empty($options)){
			if(isset($options['pay_type'])){
				$this->alipayType=$options['pay_type'];
			}
			if(isset($options['goods_type'])){
				$this->goodsType=$options['goods_type'];
			}
			if(isset($options['call_type'])){
				$this->callType=$options['call_type'];
			}
		}
	}
	
	
	
	
	/**
	 * $data array 订单信息
	 * $paytype str  	支付类型
	 * $calltype str  	起调类型
	 * $servicetype  str    购买类型（'recharge'虚拟物品,'bay'真实物品）
	 */
	public function get_order_detail($orderinfo){
// 		配置文件
		$payconfig=include 'alipay.config.php';
		$payconfig=$payconfig[$this->callType];
// 		echo $this->alipayType.DIRECTORY_SEPARATOR.$this->callType.DIRECTORY_SEPARATOR.'alipayapi.php';die;
		include $this->alipayType.DIRECTORY_SEPARATOR.$this->callType.DIRECTORY_SEPARATOR.'alipayapi.php';
		$orderdetail=(new Alipayapi($orderinfo,$payconfig))->getDetail($orderinfo, $payconfig);
		echo "<pre>";
		var_dump($orderdetail);die;
		$this->initiate($orderdetail,$this->callType,$payconfig);
	}
	
	/**
	 * 异步通知回调函数
	 */
	public function notify(){
		echo "this is alipay notify";
		
	}
	
	/**
	 * 起调支付页面
	 * $paytype str  	支付类型
	 * $calltype str  	起调类型
	 *  $servicetype  str    购买类型（'recharge'虚拟物品,'bay'真实物品）
	 *  $orderinfo   str  	订单详情
	 */
	public function initiate($orderdetail,$callType,$payconfig){
		switch (strtolower($callType)){
			case 'h5':
				//建立请求
				//过滤配置信息
				$alipay_config['partner']		= $payconfig['partner'];
				$alipay_config['key']			= $payconfig['key'];
				$alipay_config['sign_type']    	= $payconfig['sign_type'];
				$alipay_config['input_charset']	= $payconfig['input_charset'];
				$alipay_config['cacert']    	= $payconfig['cacert'];
				$alipay_config['transport']    	= $payconfig['transport'];
				include $this->callType.DIRECTORY_SEPARATOR.'alipay_submit.php';
				$alipaySubmit = new AlipaySubmit($alipay_config);
				$html_text = $alipaySubmit->buildRequestForm($orderdetail,"get", "确认");
				echo $html_text;
				
				break;
			case 'app':
				
				
				break;
				
				
			case 'native':
				
				
				break;
		}
	}
	
}
