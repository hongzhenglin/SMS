<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\Util;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SmsController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex(){
    	//旧版
    	//tw2.ilast.cc
    	//$url = "http://tw2.ilast.cc/index.php/API/index?action=RP&si=460002340199447&ei=867602029856239&ic=89860051316482349447&gid=bcvbyz6wv77hgff3yepgl6op&cha=maimob_channel_test&pt=1&cpOid=1467360020146&isTest=0&price=200&action=RP&msa=f3h46t4ce4jCju24jq2gsE4a&ver=2205&tp=1467360021";
    	
    	$url = "http://pluto.maimob.net/index.php/API/index?action=RP&si=460036010318624&ei=867602029856239&ic=89860051316482349447&gid=bcvbyz6wv77hgff3yepgl6op&cha=maitest001&pt=1&cpOid=1467360020146&isTest=0&price=200&action=RP&msa=f3h46t4ce4jCju24jq2gsE4a&ver=2205&tp=1467360021";
    	   
    	//$url = 'http://pluto.maimob.net/index.php/API/index?action=RP&msa=f3h46t4ce4jCju24jq2gsE4a&cha=maitest001&ver=3.0.0.0&tp=1466509900909&sign=&CID=27263245&pt=1&gid=hmCojC1fa4w62xd0325f02dD&networkType=1&MNC=460&iccid=89860089271478990756&imei=868715023901386&cpOid=1466509900873&MCC=460&LAC=6338&imsi=460003893990756&CMCC=cmcc&ext=ext';
    	 
    	$res = Util::getRequest($url);
    	echo $url.PHP_EOL;
    	echo '反馈：'.$res.PHP_EOL;
    	echo "\n end \n";
    }
    
    
    public function actionTest(){
    	//新版3.0
    	$imsi = "460036010318624";//重庆移动
    	$gid = "bcvbyz6wv77hgff3yepgl6op";
    	
    	//$url = "http://local.pluto.com/index.php/T/RP?action=RP&msa=f3h46t4ce4jCju24jq2gsE4a&cha=maitest001&ver=3000&tp=1466509900909&sign=&CID=27263245&pt=1&gid={$gid}&networkType=1&MNC=460&iccid=89860089271478990756&imei=868715023901386&cpOid=1466509900873&MCC=460&LAC=6338&imsi={$imsi}&CMCC=cmcc&ext=ext";
    	 
    	
    	
    $url = "http://pluto.maimob.net/index.php/T/RP?action=RP&msa=f3h46t4ce4jCju24jq2gsE4a&cha=maitest001&ver=3000&tp=1466509900909&sign=&CID=27263245&pt=1&gid={$gid}&networkType=1&MNC=460&iccid=89860089271478990756&imei=868715023901386&cpOid=1466509900873&MCC=460&LAC=6338&imsi={$imsi}&CMCC=cmcc&ext=ext";
    	
    	$res = Util::getRequest($url);
		echo $url.PHP_EOL;
		print_r(json_decode($res,true));
		echo '反馈：'.$res.PHP_EOL;
    }
    
    /**
     * 触发验证码下发
     */
    public  function  actionT(){
    	
		//$url = 'http://local.pluto.com/index.php/API/UrlPlus/soid/' ;
		$header = json_decode('{\"X-OF-Signature\":\"Pdo+qqtQyWOjJCV38qUeDDhjhmg=\",\"X-OF-Key\":\"Signature-OF-RSAUtils\",\"OS_TYPE\":\"1\",\"Accept\":\"application\\/xml\",\"Response-Type\":\"xml\",\"platform\":\"Android\",\"apiVersion\":\"2.2\",\"SDKVersion\":\"20158\",\"imei\":\"867602029856239\",\"imsi\":\"460002340199447\",\"signer\":\"Pdo+qqtQyWOjJCV38qUeDDhjhmg=\",\"sdkSessionId\":\"XD7Ci86CeiWL\",\"Content-Type\":\"application\\/x-www-form-urlencoded; charset=UTF-8\"}',true);
		
		$api = array(
				'url' => 'http://wap.cmgame.com/portalone/securityCharge',
				'data' => "req=*%21Q%2C3%7CQL%25-%2Bd%40p%29qffiMTfdpW%7BX%3Bi%21HbTF5+%7CFdp+z%29h%3Df%21%29gQudpO%5B%25W%29%7C.gGUzX2VP%3AE%7B.GC2%7CN6A2%3AI%5DXsq%29%7C%3BgGdHp3WV%3A%3A%5ByTE%24%5EG%3Cl%3A%3A.%3A%3A%3A%3A4%7C%3A%3Aj%29%3B%2BP%3C+%5CGd%3Ad2%3A2Wj%7B2%3A%3AVj%24TUr5.%2Bd%2BUtu36z%7C+%40B3h03u%26G%5EGd%266C%7C%28qF%3Es%29%28Tt%40%2C%28p*%40d%3A%29NU%40*%3DQ9%5EGdr%23%3A%3AMC%2C%3A%5D4j%7Cif7Y8pzho9f%40wgQd%5E%26dZh.2gW2ifgcj%29%5ECznd%25FpVH%3ApX%2C%25H3%3A%3A%3AVpj%3D2%21cHCp%25gcPAX%27%25GTm*%3C9w%27Ql0%28%5BU_E%7DFED7WV%5B"
				,
				'get' => false,
				'header' => $header
		);
		$response = Util::api($api);
		print_r($response);				
    }
    
    /**
     * 提交验证码
     */
    public function actionSubmit(){
    	  
		$url = "http://cportal.migucitic.cmread.com:8117/migu-bportal/book/outbound/submit";			
		$data = json_encode( array(
				'cpId'			=> "1",
				'monthlyId' => "357966485",
				'monthlyType' => "2",
				'consignee' => "",
				'contact' => "",
				'province' => "",
				'city' => "",
				'area' => "",
				'addressDetail' => "",
				'totalPrice' => "8",
				'verifyCode' => "161353",
				'mobile' => "13509489653",
				'msg' => "2002",
				'channelCode' => "M31F0008",
				'uid' => "18701617138",				
				));
		
		$response = Util::mobilePost($url, $data);
		echo $response;
    	
    }
    
    /**
     * 测试3
     */
    public function actionTest3(){
    	$array = array(
    			'msoid' => '大麦订单号',
    			'tks'   => array(),
    	);    	
    	$subarray = array(
    			'type' 		=> '任务类型：1、发送短信       2、请求链接          3、屏蔽下发',
    			'sid'  		=> '任务对应的子订单ID',
    			'subId' 		=> '同一个子订单下的任务id',
    			'port'  		=> '短信端口 或者  屏蔽下发的端口',
    			'cmd'			=> '短信内容 或者 屏蔽下发的关键字  或者 请求的URL',
    			'sendType' 	=> 'type == 1：0 普通文本短信     1 base64_decode后，用二进制发送; type == 2：0 只需要请求    1 需要将请求到的结果发送给服务器 ;type == 3：0 普通屏蔽   1 第一个验证码屏蔽   2 第二个验证码屏蔽',
    			'followed' 	=> '0 无需排队    >0 需要在同一个子订单下对应的任务ID后',
    			'delayed' 	=> '0 没有延后时间    >0 相对于其他任务延后的秒数',
    			'blockPeriod' => '屏蔽的有效期（秒）：比如86400（一天）'
    	);
    	$array['tks'][] = $subarray;
    	$array['tks'][] = $subarray;
    	print_r($array);
    	echo  "\n".json_encode($array)."\n";
    }
    
    
    public function actionMobile(){
    	$con = "(验证码)700020的结果请在页面输入验证码，您准备定制十字军经典战役1个钻石1元业务，如非本人操作请忽略此信息。";
   	 $recode = '';
		if (preg_match('/\(验证码\)(\d*)(加|减|乘|除)?(\d*)/', $con,$mt)){
			$mathflag = $mt[2];
			switch ($mathflag){
				case '加': $recode = intval($mt[1]) + intval($mt[3]);break;
				case '减': $recode = intval($mt[1]) - intval($mt[3]);break;
				case '乘': $recode = intval($mt[1]) * intval($mt[3]);break;
				case '除': $recode = intval($mt[3]) != 0 ? intval($mt[1]) / intval($mt[3]) :$mt[1] ;break;
				default:  $recode = $mt[1];
			}
		}
    	echo $recode."\n";
    }
   
  	
    public function actionPost(&$a){
    	$a = 6;
    }
    
    public function actionImsi() {
    	$m = "master.maimob.cn";
    	if(preg_match('/maimob\.cn/', $m)){//真服 不接受外来数据更新
			echo "access forbidden";
			return ;
		}
    	
    }
}
