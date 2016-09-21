<?php
namespace app\models;

use Yii;
use yii\base\Model;

class Util {
	
	
	/**
	 * 请求API
	 * @param 请求API string $url
	 * @param 请求数据 array $data 
	 * @param 请求方法 string $GET (TRUE：GET默认 ； FALSE ：POST)
	 * @param 返回数据格式 string $format (text、xml、stream、json默认)
	 * @return mixed 数组或字符串
	 */
	public static function requestAPI($url,$data=array(),$GET= TRUE,$format='json'){
		if($GET){
			$url .= http_build_query($data);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; 
		}else{
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch,CURLOPT_COOKIEJAR,null);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);					
		}
		$response = curl_exec($ch);		
		switch (strtolower($format)){
			case 'text' : 
				$response = trim($response);
				break;
			case 'xml' :
				$response = json_decode(json_encode((array) simplexml_load_string($response)), true);
				break;
			case 'stream' :				
				$response = file_get_contents("php://input");
				break;
			default: 
				$response = json_decode($response,true);
				break;
		}		
		return $response;
	}
	
	
	/**
	 * 访问api- 请求参数为数组，返回内容也为数组
	 * @param 数组 $api=array('url'=>'','data'=>array(),'get'=>TRUE,'format'=>'json')
	 * @param 请求API string $url
	 * @param 请求数据 array或者xml $data
	 * @param 请求方法 boole $GET (TRUE：GET默认 ； FALSE ：POST)
	 * @param 返回数据格式 string $format (text、xml、json默认)
	 * @return mixed 数组
	 */
	public static function api($api){
		$res = array(
				'flag'			=> 0,
				'msg'			=> 'error',
				'url'			=> '',
				'string' 		=> '',
				'array' 		=> array(),
				'beginTime' => '',
				'endTime' 	=> '',
		);
		if(!is_array($api)){
			$res['msg'] = 'api is not array ';
			return $res;
		}		
		$url 		= isset($api['url']) ? $api['url'] : '';
		$data 		= isset($api['data']) ? $api['data'] : array();
		$get 		= isset($api['get']) ? $api['get'] : true;
		$format 	= isset($api['format']) ? $api['format'] : 'json';
		if(!is_string($url) || null == $url){
			$res['msg'] = 'url is null ';
			return $res;
		}
		if($get){
			$url .= is_array($data) ? http_build_query($data) : $data;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ;
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
			$header = self::_array($api, 'header',array());
			if ($header) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			}
		}else{
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch,CURLOPT_COOKIEJAR,null);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,true);
			$header = self::_array($api, 'header',array());
			if ($header) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			}
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
			$url .= is_array($data) ? http_build_query($data): $data;			
		}
		$beginTime = date('Y-m-d H:i:s');
		$response =  trim(curl_exec($ch));		
		$endTime = date('Y-m-d H:i:s');
		if(false === $response){
			$res['msg'] = curl_error($ch);
			return $res;
		}
		curl_close($ch);
		switch (strtolower($format)){
			case 'text' :				
				$arr = array('result' => $response);
				break;
			case 'xml' :
				$arr = json_decode(json_encode((array) simplexml_load_string($response)), true);
				break;
			default:
				$arr = json_decode($response,true);
				break;
		}
		$res = array(
				'flag' 		=> 1,
				'msg' 			=> 'success',
				'url'			=> $url,
				'string' 		=> $response,
				'array' 		=> $arr,
				'beginTime' => $beginTime,
				'endTime' 	=> $endTime,
		);
		return $res;
	}
	
	/**
	* write log into file
	*/
	public static function plog($str, $logFile, $withTime=false, $newLine=false){
		$dayDir = dirname(__FILE__) . "/data/";
		if (!file_exists($dayDir)) {
			mkdir($dayDir);
		}
		$fp = fopen("{$dayDir}/{$logFile}","a");
		flock($fp, LOCK_EX) ;
		if ($str) {
			$timeStr = $withTime ? date('Y-m-d H:i:s', time()) . ' > ' : '';
			$nlStr = $newLine ? "\n" : '';
			fwrite($fp, $nlStr . $timeStr . $str);
		}
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	
	public static function hash($num, $len = 5) {
		$ceil = bcpow(62, $len);
		$primes = array_keys(self::$golden_primes);
		$prime = $primes[$len];
		$dec = bcmod(bcmul($num, $prime), $ceil);
		$hash = self::base62($dec);
		return str_pad($hash, $len, "0", STR_PAD_LEFT);
	}
	
	public static function base62($int) {
		$key = "";
		while(bccomp($int, 0) > 0) {
			$mod = bcmod($int, 62);
			$key .= chr(self::$chars62[$mod]);
			$int = bcdiv($int, 62);
		}
		return strrev($key);
	}
	
	private static $chars62 = array(
			0=>48,1=>49,2=>50,3=>51,4=>52,5=>53,6=>54,7=>55,8=>56,9=>57,10=>65,
			11=>66,12=>67,13=>68,14=>69,15=>70,16=>71,17=>72,18=>73,19=>74,20=>75,
			21=>76,22=>77,23=>78,24=>79,25=>80,26=>81,27=>82,28=>83,29=>84,30=>85,
			31=>86,32=>87,33=>88,34=>89,35=>90,36=>97,37=>98,38=>99,39=>100,40=>101,
			41=>102,42=>103,43=>104,44=>105,45=>106,46=>107,47=>108,48=>109,49=>110,
			50=>111,51=>112,52=>113,53=>114,54=>115,55=>116,56=>117,57=>118,58=>119,
			59=>120,60=>121,61=>122
	);
	
	/* Key: Next prime greater than 62 ^ n / 1.618033988749894848 */
	/* Value: modular multiplicative inverse */
	private static $golden_primes = array(
			'1'                  => '1',
			'41'                 => '59',
			'2377'               => '1677',
			'147299'             => '187507',
			'9132313'            => '5952585',
			'566201239'          => '643566407',
			'35104476161'        => '22071637057',
			'2176477521929'      => '294289236153',
			'134941606358731'    => '88879354792675',
			'8366379594239857'   => '7275288500431249',
			'518715534842869223' => '280042546585394647'
	);
	

	/**
	 * 将 新类型数据,返回给
	 * todo ： 待优化  触发验证码的URL 必须在本次请求结束之前返给
	 * 1、API-Single
	 * 2、API-Double
	 * 3、SMS+
	 * 4、URL+
	 * 5、MultUrl
	 * 6、MultSMS
	 */
	public static function switchCh($arr){
		$arr = array('type' => '', 'sid' => '', 'detail' => array(
				'sendList' 		=> array(array('port' =>'','sms' => '','sendMethod' =>'','time'=>''),array('port' =>'','sms' => '','sendMethod' =>'','time'=>'')),//需要发送的短信
				'recallList' 	=> array(array('port' =>'','sms' => '','time'=>''),array('port' =>'','sms' => '','time'=>'')),	//需要提交的短信
				'blockList' 		=> array(array('port' =>'','keys' => '|'),array('port' =>'','keys' => '|')),	//需要屏蔽的短信
				'recallUrl'		=> '' //需要回调的URL链接
		));
		$old 		= $arr['detail'] ;
		$type 		= $arr['type'];
		$url    	= $arr['recallUrl'];
		switch ($type) {
			case 1:
				$new = array(
				'pp' 	=> $old['sendList'][0]['port'],
				'pc' 	=> $old['sendList'][0]['sms'],
				'b'		=> $old['sendList'][0]['sendMethod'],
				'bkList' => $old['blockList'],
				);
				break;
			case 2:
				$new = array(
				'pp1' => $old['sendList'][0]['port'],
				'pc1' => $old['sendList'][0]['sms'],
				'b1'	=> $old['sendList'][0]['sendMethod'],
				'pp2' => $old['sendList'][1]['port'],
				'pc2' => $old['sendList'][1]['sms'],
				'b2'	=> $old['sendList'][1]['sendMethod'],
				'bkList' => $old['blockList'],
				);
				break;
			case 3:
				$new = array(
				'pl' => array(array('pp'=>'','pc'=>'','b'=>''),array('pp'=>'','pc'=>'','b'=>'')),
				'cp' => $old['recallList'][0]['port'],
				'ck' => $old['recallList'][0]['sms'],
				'bkList' => $old['blockList'],
				);
				break;
			case 4:
				$new = array(
				'url' => $url,
				'cp' => $old['recallList'][0]['port'],
				'ck' => $old['recallList'][0]['sms'],
				'bkList' => $old['blockList'],
				);
				break;
			case 5:
				$new = array(
				'url' => $url,
				'cp' => $old['recallList'][0]['port'],
				'ck' => $old['recallList'][0]['sms'],
				'cp2' => $old['recallList'][1]['port'],
				'ck2' => $old['recallList'][1]['sms'],
				'bkList' => $old['blockList'],
				);
				break;
			case 6:
				$new = array(
				'pl' => array(array('pp'=>'','pc'=>'','b'=>''),array('pp'=>'','pc'=>'','b'=>'')),
				'cp' => $old['recallList'][0]['port'],
				'ck' => $old['recallList'][0]['sms'],
				'cp2' => $old['recallList'][1]['port'],
				'ck2' => $old['recallList'][1]['sms'],
				'bkList' => $old['blockList'],
				);
				break;
		}
	
	}

	/**
	 * 根据标识符分割字符串： 常用于获取端口和指令
	 * @param 字符串 $con
	 * @param 标识符 $sign
	 */
	public static function splitPortSMSBySign($con,$sign){
		if(preg_match("/(.*){$sign}(.*)/", $con,$mt)){
			$pp = $mt[1];
			$pc = $mt[2];
		}else{
			$pp = '';
			$pc = '';
		}
		return array('pp'=> $pp, 'pc'=> $pc);
	}
	

	/**
	 * 读取数组中给定key的val
	 * @param	string	$key
	 */
	public static function _array($arr, $key, $val=0){
		return isset($arr[$key]) ? $arr[$key] : $val;
	}
	
	
	/**
	 * post一个数组
	 * @param unknown $url
	 * @param unknown $array
	 * @param unknown $header
	 * @return mixed
	 */
	public static function postArray($url, $array, $header=array(),$timeout=0){
		$str = '';
		if ($array) {
			foreach ($array as $k => $v) {
				$str .= $k . '=' . urlencode($v) . '&';
			}
			$str = substr($str, 0, -1);
		}
		return self::postRequest($url, $str, $header);
	}
	
	/**
	 * 发出一个POST请求
	 * @param String $url	请求的地址
	 * @param String $data	数据
	 */
	public static function postRequest($url, $data, $header=array(),$timeout=0){
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch,CURLOPT_COOKIEJAR,null);
		if ($header) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		if ($timeout && is_numeric($timeout) && $timeout > 0) {
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		}
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$content = curl_exec($ch);
		return $content;
	}
	
	/**
	 * 发出一个Get请求
	 * @param String $url	请求的地址
	 */
	public static function getRequest($url){
		$ch = curl_init($url) ;
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
		return curl_exec($ch) ;
	}
	
	// 将UNICODE编码后的内容进行解码
	public static function unicode_decode($name){
		// 转换编码，将Unicode编码转换成可以浏览的utf-8编码
		$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
		preg_match_all($pattern, $name, $matches);
		if (!empty($matches))
		{
			$name = '';
			for ($j = 0; $j < count($matches[0]); $j++)
			{
				$str = $matches[0][$j];
				if (strpos($str, '\\u') === 0)
				{
					$code = base_convert(substr($str, 2, 2), 16, 10);
					$code2 = base_convert(substr($str, 4), 16, 10);
					$c = chr($code).chr($code2);
					$c = iconv('UCS-2', 'UTF-8', $c);
					$name .= $c;
				}
				else
				{
					$name .= $str;
				}
			}
		}
		return $name;
	}
	
	//字符串转十六进制
	public static function strToHex($string){
		$hex="";
		for($i=0;$i<strlen($string);$i++)
			$hex.=dechex(ord($string[$i]));
		$hex=strtoupper($hex);
		return $hex;
	}
	
	//十六进制转字符串
	public static function hexToStr($hex){
		$string="";
		for($i=0;$i<strlen($hex)-1;$i+=2)
			$string.=chr(hexdec($hex[$i].$hex[$i+1]));
		return  $string;
	}
	
	/**
	 * 数组转字符串
	 * @param array $array
	 */
	public static function arrayToStr(array $array){
		$str = '';
		if(!is_array($array)){
			return $str;
		}
		foreach ($array as $key=>$value){
			if(is_string($value)){
				$str .= "\n{$key}:{$value}";
			}elseif (is_array($value)){
				$value = self::arrayToStr($value);
				$str .= "\n{$key}:{$value}";
			}elseif (is_bool($value)){
				$value = $value == true ? 'true' : 'false';
				$str .= "\n{$key}:{$value}";
			}
		}
		return $str;
	}
	

	/**
	 * 格式化API响应结果为数组
	 * @param string $response
	 * @param string $format: json(默认)、xml
	 * @return multitype:
	 */
	public static function formatResponse($response,$format='json'){
		switch (strtolower($format)){
			case 'xml' :
				$arr = json_decode(json_encode((array) simplexml_load_string($response)), true);
				break;
			default:
				$arr = json_decode($response,true);
				break;
		}
		return $arr;
	}
	
	
	public static function mobilePost($url,$data,$headers=array()) {
		$headers = array();
		$headers[] = 'Client Address: /192.168.199.240';
		$headers[] = 'Host: cportal.migucitic.cmread.com:8117';
		$headers[] = 'Connection: keep-alive';
		$headers[] = 'Content-Length: 255';
		$headers[] = 'Accept: application/json';
		$headers[] = 'Origin: http://cportal.migucitic.cmread.com:8117';
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 4.4.4; Che1-CL20 Build/Che1-CL20) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/37.0.0.0 Mobile MQQBrowser/6.2 TBS/036523 Safari/537.36 V1_AND_SQ_6.3.6_372_YYB_D QQ/6.3.6.2790 NetType/WIFI WebP/0.3.0 Pixel/720';
		$headers[] = 'Content-Type: application/json;charset=UTF-8';
		$headers[] = 'Accept-Encoding: gzip,deflate';
		$headers[] = 'Accept-Language: zh-CN,en-US;q=0.8';
		$headers[] = 'Referer :http://cportal.migucitic.cmread.com:8117/zxCallOutHtml5/html/passwordPage.html?nid=357966485&cm=M31F0008&uid=18701617138';
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($ch,CURLOPT_COOKIEJAR,null);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$response =  trim(curl_exec($ch));
		return $response;
	}

	/**
	 * 测试根据imsi获取手机号前几位的成功率
	 */
	public function actionTest(){
		//从数据库获取测试所需数据源
		$simCards = SimCard::model()->findAll("id > 1");
		//处理数据
		if (!$simCards){
			return '';
		}
		$i = 0;
		$total = 0;
		//遍历执行测试
		for ($length = 2;$length<= 7;$length++	){
			foreach ($simCards as $simCard){
				$imsi = $simCard->imsi;
				//过滤掉不合法的数据
				if (strlen($imsi) != 15  || strlen($simCard->mobile) != 11 ){
					continue;
				}
				//记录有效测试的总次数
				$total++;
				//执行一次测试：根据imsi获取手机号前7位
				$mobile = self::GetPhoneByImsi($imsi);
				//echo 'imsi:'.$imsi . '		mobile:'. $simCard->mobile.'		result:'. $mobile .PHP_EOL;
				//记录测试成功的次数
				if (substr($mobile,0,$length) == substr($simCard->mobile,0,$length)){
					$i++;
				}
			}
			$rate = $total != 0 ? sprintf("%10.2f", $i/$total * 100)  :'' ;
			echo "length: $length,   total: $total ,success : $i , successRate:".$rate .'%'  .PHP_EOL;
		}
	}
	
	/**
	 * 反编译IMSI获得手机前7位 判断区域
	 * @param unknown $imsi
	 * >返回手机号前七位伪码
	 */
	public static function GetPhoneByImsi($imsi){
		$s56789 = "56789";
		$strDigit;
		
		$beginStr = substr($imsi, 0,5);
	
		if ($beginStr == '46000'){
			$h1 = substr($imsi, 5,1);
			$h2 = substr($imsi, 6,1);
			$h3 = substr($imsi, 7,1);
			$st = substr($imsi, 8,1);
			$h0 = substr($imsi, 9,1);
			echo ""; 
			if ( strstr($s56789,$st)){
				return "13" .$st . "0" . $h1 . $h2 . $h3;
			} else {
				$tempint = intval($st) + 5;
				return "13" . $tempint . $h0 . $h1 . $h2 . $h3;
			}
		}
		 
		 
		if ($beginStr == '46002'){
			$strDigit = substr($imsi, 5,1);
			$h0 = substr($imsi, 6,1);
			$h1 = substr($imsi, 7,1);
			$h2 = substr($imsi, 8,1);
			$h3 = substr($imsi, 9,1);
			switch ($strDigit) {
				case '0' :
					$mobile = "134" ;
					break;
				case '1' :
					$mobile = "151" ;
					break;
				case '2' :
					$mobile = "152" ;
					break;
				case '3' :
					$mobile = "150" ;
					break;
				case '5' :
					$mobile = "183" ;
					break;
				case '6' :
					$mobile = "182" ;
					break;
				case '7' :
					$mobile = "187" ;
					break;
				case '8' :
					$mobile = "158" ;
					break;
				case '9' :
					$mobile = "159" ;
					break;
				default :
					$mobile = '';
					break;
			}
			if ($mobile){
				return $mobile . $h0 . $h1 . $h2 . $h3;
			}
		}
	
		 
	
		 
		if ($beginStr == '46003'){
			$strDigit = substr($imsi, 5,1);
			$h0 = substr($imsi, 6,1);
			$h1 = substr($imsi, 7,1);
			$h2 = substr($imsi, 8,1);
			$h3 = substr($imsi, 9,1);
			if ($strDigit){
				return "153" . $h0 . $h1 . $h2 . $h3;
			}
			if ($h0 == "9" && $h1 . $$h2 == "00"){
				return "13301" . $h3 .substr($imsi,10,1);
			}
			return "133" . $h0 . $h1 . $h2 . $h3;
		}
		if ($beginStr == '46011'){
			$h0 = substr($imsi, 6,1);
			$h1 = substr($imsi, 7,1);
			$h2 = substr($imsi, 8,1);
			$h3 = substr($imsi, 9,1);
			return "177" . $h0 . $h1 . $h2 . $h3;
		}
		 
		if ($beginStr == '46007'){
			$strDigit = substr($imsi, 5,1);
			$h0 = substr($imsi, 6,1);
			$h1 = substr($imsi, 7,1);
			$h2 = substr($imsi, 8,1);
			$h3 = substr($imsi, 9,1);
			 
			if ($strDigit == "5"){
				return "178" . $h0 . $h1 . $h2 . $h3;
			}else if ($strDigit == "7") {
				return "157" . $h0 . $h1 . $h2 . $h3;
			}else if ($strDigit == "8"){
				return "188" . $h0 . $h1 . $h2 . $h3;
			}else if ($strDigit == "9"){
				return "147" . $h0 . $h1 . $h2 . $h3;
			}
		}
		 
		 
		if ($beginStr == '46001'){
			//中国联通，只有46001这一个IMSI号码段
			$h1 = substr($imsi, 5,1);
			$h2 = substr($imsi, 6,1);
			$h3 = substr($imsi, 7,1);
			$h0 = substr($imsi, 8,1);
			$strDigit = substr($imsi, 9,1);
			if ($strDigit == "0" || $strDigit == "1"){
				return "130" . $h0 . $h1 . $h2 . $h3;
			}else if ($strDigit == "9"){
				return "131" . $h0 . $h1 . $h2 . $h3;
			}else if ($strDigit == "2") {
				return "132" . $h0 . $h1 . $h2 . $h3;
			}else if ($strDigit == "3"){
				return "156" . $h0 . $h1 . $h2 . $h3;
			}else if ($strDigit == "4"){
				return "155" . $h0 . $h1 . $h2 . $h3;
			}else if ($strDigit == "6"){
				return "186" . $h0 . $h1 . $h2 . $h3;
			}else if ($strDigit == "7"){
				return "145" . $h0 . $h1 . $h2 . $h3;
			}
		}
		 
		return '';
		 
	}
	
}