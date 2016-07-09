<?php  
$appconfig=array(
	//合作身份者id，以2088开头的16位纯数字
	'partenr'			=>'',
		
	//安全检验码，以数字和字母组成的32位字符
	'key'				=>'',
		
	//卖家支付宝帐户
	'seller_email'		=>'',
		
	//签名方式 不需修改
	'sign_type'			=>strtoupper('MD5'),
		
	//字符编码格式 目前支持 gbk 或 utf-8
	'input_charset'		=>strtolower('utf-8'),
		
	//ca证书路径地址，用于curl中ssl校验
	'cacert'			=>getcwd().'\\cacert.pem',
		
	//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
	'transport'			=>'http',
		
	//支付类型为即时到帐（无需修改）
	'payment_type'		=>'1',
);


$h5config=array(
	
		//合作身份者id，以2088开头的16位纯数字
		'partner'			=>'fadfasd',
		
		//安全检验码，以数字和字母组成的32位字符
		'key'				=>'fasdfasd',
		
		//卖家支付宝帐户
		'seller_email'		=>'fasdfasdf',
		
		//签名方式 不需修改
		'sign_type'			=>strtoupper('MD5'),
		
		//字符编码格式 目前支持 gbk 或 utf-8
		'input_charset'		=>strtolower('utf-8'),
		
		//ca证书路径地址，用于curl中ssl校验
		'cacert'			=>getcwd().'\\cacert.pem',
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		'transport'			=>'http',
		
		//支付类型为即时到帐（无需修改）
		'payment_type'		=>'1',
);


$nativeconfig=array(
		//合作身份者id，以2088开头的16位纯数字
		'partenr'			=>'',
		
		//安全检验码，以数字和字母组成的32位字符
		'key'				=>'',
		
		//卖家支付宝帐户
		'seller_email'		=>'',
		
		//签名方式 不需修改
		'sign_type'			=>strtoupper('MD5'),
		
		//字符编码格式 目前支持 gbk 或 utf-8
		'input_charset'		=>strtolower('utf-8'),
		
		//ca证书路径地址，用于curl中ssl校验
		'cacert'			=>getcwd().'\\cacert.pem',
		
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		'transport'			=>'http',
		
		//支付类型为即时到帐（无需修改）
		'payment_type'		=>'1',
		
);

return array(
	'app'=>$appconfig,
	'h5'=>$h5config,
	'native'=>$nativeconfig
);
?>