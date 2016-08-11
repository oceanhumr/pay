<?php
namespace Pay\Unpay;
use Pay\PayBase;
require_once dirname(__DIR__).DIRECTORY_SEPARATOR.'PayBase.class.php';
require_once 'UnionUtil.class.php';
class Unpay implements PayBase{
	

	/**
	 * 获取订单详细信息
	 * (non-PHPdoc)
	 * @see PayBase::get_order_detail()
	 */
	private $baseinfo=array(
		'version'=>'5.0.0',				//版本号
		'encoding'=>'utf-8',			//编码方式
		'txnType'=>'01',				//交易类型
		'txnSubType'=>'01',				//交易子类
		'bizType'=>'000201',			//业务类型
		'fromUrl'=>SDK_FRONT_NOTIFY_URL,//前台跳转
		'backUrl'=>SDK_BACK_NOTIFY_URL,	//服务器回调
		'signMethod'=>'01',				//签名方法
		'channelType'=>'08',			//渠道类型，07-PC，08-手机
		'accessType'=>'0',				//接入类型
		'currencyCode'=>'156', 			//交易币种，境内商户固定156
		//一下内容自定义
		'merId' => '',//商户id
		'orderId' => '',				//订单号
		'txnTime' =>'',				//下单时间
		'txnAmt' => '',					//交易金额(单位分)
	);


	public function get_order_detail($data){
		$postinfo=$this->baseinfo;
		switch ($callType){
			case 'app':
				$postinfo['merId']=APP_APPID;
				break;
			case 'h5':
				$postinfo['merId']=WEB_APPID;
				break;
		}
		$postinfo['orderId']=$data['orderId'];
		$postinfo['txnTime']=date('YmdHis',$data['create_time']);
		$postinfo['txnAmt']=$data['money']*100;
		$this->sign($postinfo);
		$this->initiate('unpay',$callType,$serviceType='bay',$postinfo);
	}
	
	/**
	 * 异步通知回调函数
	 * (non-PHPdoc)
	 * @see PayBase::notify()
	 */
	public function notify(){
		
		
	}
	
	/**
	 * 起调支付页面
	 * (non-PHPdoc) 
	 * @see PayBase::initiate()
	 */
	public function initiate($orderdetail,$callType,$payconfig){
		switch ($callType){
			case 'app':
				$this->initiate_app($orderinfo);
				break;
			case 'h5':
				$this->initiate_h5($orderinfo);
				break;
			case 'native':
				$this->initiate_native($orderinfo);
				break;
		}

	}
	
	public function initiate_h5($orderinfo){
		//h5的处理方法nt.pay_form.submit();">
		$encodeType ='UTF-8';
		$html = <<<eot
<html>
<head>
    <meta http-equiv="Content-Type" content="textml; charset={$encodeType}" />
</head>
<body onload="javascript:document.pay_form.submit();">
    <form id="pay_form" name="pay_form" action={self::SDK_H5_REQUEST_URL} method="post">
	
eot;
		foreach ( $orderinfo as $key => $value ) {
			$html .= "    <input type=\"hidden\" name=\"{$key}\" id=\"{$key}\" value=\"{$value}\" />\n";
		}
		$html .= <<<eot
    </form>
</body>
<ml>
eot;

		return $html;
	}


	public function initiate_app($orderinfo){
		//app处理
	}


	public function initiate_native($orderinfo){
		//扫码
	}

	


	
	
}