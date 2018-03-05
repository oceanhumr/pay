<?php
/**
 * key1=value1&key2=value2转array
 * @param $str key1=value1&key2=value2的字符串
 * @param $$needUrlDecode 是否需要解url编码，默认不需要
 */
function parseQString($str, $needUrlDecode=false){
    $result = array();
    $len = strlen($str);
    $temp = "";
    $curChar = "";
    $key = "";
    $isKey = true;
    $isOpen = false;
    $openName = "\0";

    for($i=0; $i<$len; $i++){
        $curChar = $str[$i];
        if($isOpen){
            if( $curChar == $openName){
                $isOpen = false;
            }
            $temp = $temp . $curChar;
        } elseif ($curChar == "{"){
            $isOpen = true;
            $openName = "}";
            $temp = $temp . $curChar;
        } elseif ($curChar == "["){
            $isOpen = true;
            $openName = "]";
            $temp = $temp . $curChar;
        } elseif ($isKey && $curChar == "="){
            $key = $temp;
            $temp = "";
            $isKey = false;
        } elseif ( $curChar == "&" && !$isOpen){
            putKeyValueToDictionary($temp, $isKey, $key, $result, $needUrlDecode);
            $temp = "";
            $isKey = true;
        } else {
            $temp = $temp . $curChar;
        }
    }
    putKeyValueToDictionary($temp, $isKey, $key, $result, $needUrlDecode);
    return $result;
}


function putKeyValueToDictionary($temp, $isKey, $key, &$result, $needUrlDecode) {
    if ($isKey) {
        $key = $temp;
        if (strlen ( $key ) == 0) {
            return false;
        }
        $result [$key] = "";
    } else {
        if (strlen ( $key ) == 0) {
            return false;
        }
        if ($needUrlDecode)
            $result [$key] = urldecode ( $temp );
        else
            $result [$key] = $temp;
    }
}

/**
 * 字符串转换为 数组
 *
 * @param unknown_type $str
 * @return multitype:unknown
 */
function convertStringToArray($str) {
    return parseQString($str);
}

/**
 * 压缩文件 对应java deflate
 *
 * @param unknown_type $params
 */
function deflate_file(&$params) {

    foreach ( $_FILES as $file ) {
        if (file_exists ( $file ['tmp_name'] )) {
            $params ['fileName'] = $file ['name'];

            $file_content = file_get_contents ( $file ['tmp_name'] );
            $file_content_deflate = gzcompress ( $file_content );

            $params ['fileContent'] = base64_encode ( $file_content_deflate );
        } else {
            //$log->LogInfo ( ">>>>文件上传失败<<<<<" );
        }
    }
}


/**
 * 讲数组转换为string
 *
 * @param $para 数组
 * @param $sort 是否需要排序
 * @param $encode 是否需要URL编码
 * @return string
 */
function createLinkString($para, $sort, $encode) {
    if($para == NULL || !is_array($para))
        return "";

    $linkString = "";
    if ($sort) {
        $para = argSort ( $para );
    }
    while ( list ( $key, $value ) = each ( $para ) ) {
        if ($encode) {
            $value = urlencode ( $value );
        }
        $linkString .= $key . "=" . $value . "&";
    }
    // 去掉最后一个&字符
    $linkString = substr ( $linkString, 0, count ( $linkString ) - 2 );

    return $linkString;
}

/**
 * 对数组排序
 *
 * @param $para 排序前的数组
 *        	return 排序后的数组
 */
function argSort($para) {
    ksort ( $para );
    reset ( $para );
    return $para;
}

function sign(&$params, $cert_path, $cert_pwd) {
    global $log;
    //$log->LogInfo ( '=====签名报文开始======' );
    if(isset($params['signature'])){
        unset($params['signature']);
    }
    // 转换成key=val&串
    $params_str = createLinkString ( $params, true, false );
    //$log->LogInfo ( "签名key=val&...串 >" . $params_str );

    $params_sha1x16 = sha1 ( $params_str, FALSE );
    //$log->LogInfo ( "摘要sha1x16 >" . $params_sha1x16 );

    $private_key = getPrivateKey ( $cert_path, $cert_pwd );
    // 签名
    $sign_falg = openssl_sign ( $params_sha1x16, $signature, $private_key, OPENSSL_ALGO_SHA1 );
    if ($sign_falg) {
        $signature_base64 = base64_encode ( $signature );
        //$log->LogInfo ( "签名串为 >" . $signature_base64 );
        $params ['signature'] = $signature_base64;
    } else {
        //$log->LogInfo ( ">>>>>签名失败<<<<<<<" );
    }
    //$log->LogInfo ( '=====签名报文结束======' );
}

/**
 * 验签
 *
 * @param String $params_str
 * @param String $signature_str
 */
function verify($params) {
    global $log;
    // 公钥
    $public_key = getPulbicKeyByCertId ( $params ['certId'] );
//	echo $public_key.'<br/>';
    // 签名串
    $signature_str = $params ['signature'];
    unset ( $params ['signature'] );
    $params_str = createLinkString ( $params, true, false );
    //$log->LogInfo ( '报文去[signature] key=val&串>' . $params_str );
    $signature = base64_decode ( $signature_str );
//	echo date('Y-m-d',time());
    $params_sha1x16 = sha1 ( $params_str, FALSE );
    //$log->LogInfo ( '摘要shax16>' . $params_sha1x16 );
    $isSuccess = openssl_verify ( $params_sha1x16, $signature,$public_key, OPENSSL_ALGO_SHA1 );
    //$log->LogInfo ( $isSuccess ? '验签成功' : '验签失败' );
    return $isSuccess;
}

/**
 * 根据证书ID 加载 证书
 *
 * @param unknown_type $certId
 * @return string NULL
 */
function getPulbicKeyByCertId($certId) {
    //global $log;
    //log::LogInfo ( '报文返回的证书ID>' . $certId );
    // 证书目录
    $cert_dir = SDK_VERIFY_CERT_DIR;
    //Log::LogInfo ( '验证签名证书目录 :>' . $cert_dir );
    $handle = opendir ( $cert_dir );
    if ($handle) {
        while ( $file = readdir ( $handle ) ) {
            clearstatcache ();
            $filePath = $cert_dir . '/' . $file;
            if (is_file ( $filePath )) {
                if (pathinfo ( $file, PATHINFO_EXTENSION ) == 'cer') {
                    Log::LogInfo($filePath);
                    if (getCertIdByCerPath ( $filePath ) == $certId) {
                        closedir ( $handle );
                        //Log::LogInfo("加载验签证书成功");
                        //$log->LogInfo ( '加载验签证书成功' );
                        return getPublicKey ( $filePath );
                    }
                }
            }
        }
        //Log::LogInfo ( '没有找到证书ID为[' . $certId . ']的证书' );
    } else {
        //Log::LogInfo ( '证书目录 ' . $cert_dir . '不正确' );
    }
    closedir ( $handle );
    return null;
}

/**
 * 取证书ID(.pfx)
 *
 * @return unknown
 */
function getSignCertId($cert_path, $cert_pwd) {
    $pkcs12certdata = file_get_contents ( $cert_path );
    openssl_pkcs12_read ( $pkcs12certdata, $certs, $cert_pwd );
    $x509data = $certs ['cert'];
    openssl_x509_read ( $x509data );
    $certdata = openssl_x509_parse ( $x509data );
    $cert_id = $certdata ['serialNumber'];
    return $cert_id;
}

/**
 * 取证书ID(.cer)
 *
 * @param unknown_type $cert_path
 */
function getCertIdByCerPath($cert_path) {
    $x509data = file_get_contents ( $cert_path );
    openssl_x509_read ( $x509data );
    $certdata = openssl_x509_parse ( $x509data );
    $cert_id = $certdata ['serialNumber'];
    return $cert_id;
}



/**
 * 取证书公钥 -验签
 *
 * @return string
 */
function getPublicKey($cert_path) {
    return file_get_contents ( $cert_path );
}
/**
 * 返回(签名)证书私钥 -
 *
 * @return unknown
 */
function getPrivateKey($cert_path=SDK_SIGN_CERT_PATH, $cert_pwd=SDK_SIGN_CERT_PWD) {
    $pkcs12 = file_get_contents ( $cert_path );
    openssl_pkcs12_read ( $pkcs12, $certs, $cert_pwd );
    return $certs ['pkey'];
}



/**
 * 密码转PIN
 * Enter description here ...
 * @param $spin
 */
function  Pin2PinBlock( &$sPin )
{
    //	$sPin = "123456";
    $iTemp = 1;
    $sPinLen = strlen($sPin);
    $sBuf = array();
    //密码域大于10位
    $sBuf[0]=intval($sPinLen, 10);

    if($sPinLen % 2 ==0)
    {
        for ($i=0; $i<$sPinLen;)
        {
            $tBuf = substr($sPin, $i, 2);
            $sBuf[$iTemp] = intval($tBuf, 16);
            unset($tBuf);
            if ($i == ($sPinLen - 2))
            {
                if ($iTemp < 7)
                {
                    $t = 0;
                    for ($t=($iTemp+1); $t<8; $t++)
                    {
                        $sBuf[$t] = 0xff;
                    }
                }
            }
            $iTemp++;
            $i = $i + 2;	//linshi
        }
    }
    else
    {
        for ($i=0; $i<$sPinLen;)
        {
            if ($i ==($sPinLen-1))
            {
                $mBuf = substr($sPin, $i, 1) . "f";
                $sBuf[$iTemp] = intval($mBuf, 16);
                unset($mBuf);
                if (($iTemp)<7)
                {
                    $t = 0;
                    for ($t=($iTemp+1); $t<8; $t++)
                    {
                        $sBuf[$t] = 0xff;
                    }
                }
            }
            else
            {
                $tBuf = substr($sPin, $i, 2);
                $sBuf[$iTemp] = intval($tBuf, 16);
                unset($tBuf);
            }
            $iTemp++;
            $i = $i + 2;
        }
    }
    return $sBuf;
}
/**
 * Enter description here ...
 * @param $sPan
 */
function FormatPan(&$sPan)
{
    $iPanLen = strlen($sPan);
    $iTemp = $iPanLen - 13;
    $sBuf = array();
    $sBuf[0] = 0x00;
    $sBuf[1] = 0x00;
    for ($i=2; $i<8; $i++)
    {
        $tBuf = substr($sPan, $iTemp, 2);
        $sBuf[$i] = intval($tBuf, 16);
        $iTemp = $iTemp + 2;
    }
    return $sBuf;
}

function Pin2PinBlockWithCardNO(&$sPin, &$sCardNO)
{
    global $log;
    $sPinBuf = Pin2PinBlock($sPin);
    $iCardLen = strlen($sCardNO);

    if ($iCardLen <= 10)
    {
        return (1);
    }
    elseif ($iCardLen==11){
        $sCardNO = "00" . $sCardNO;
    }
    elseif ($iCardLen==12){
        $sCardNO = "0" . $sCardNO;
    }
    $sPanBuf = FormatPan($sCardNO);
    $sBuf = array();

    for ($i=0; $i<8; $i++)
    {

        $sBuf[$i] = vsprintf("%c", ($sPinBuf[$i] ^ $sPanBuf[$i]));
    }
    unset($sPinBuf);
    unset($sPanBuf);
    //		return $sBuf;
    $sOutput = implode("", $sBuf);	//数组转换为字符串
    return $sOutput;
}

