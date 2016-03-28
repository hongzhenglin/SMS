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
		}else{
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
			curl_setopt($ch,CURLOPT_COOKIEJAR,null);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_POST,true);
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
	
}