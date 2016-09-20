<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\commands;

use yii\console\Controller;
use app\models\Util;
use app\models\database\Province;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SmsController extends Controller {
	/**
	 * This command echoes what you have entered as the message.
	 *
	 * @param string $message
	 *        	the message to be echoed.
	 */
	public function actionIndex() {
		$province = Province::find ()->all ();
		print_r ( $province );
		
		// $response = Util::api($api);
		// print_r($response);
		
		echo "\n end \n";
	}
	public function actionTest() {
		$response = <<<EOC
{
"ResultCode":"000",
"ResultDescription":"校验通过,生成短信成功;",
"MoSms":[
{"MoSmsMsg":"0000445986S553/%05696984f00a8301EC5w94UILS|1bubZKDbcDW\\fcpBg==7o?X696~8J4753o320)Rm315e72543fM000|0H00000O$(DFN1iwL0aT5cY1=63YjRxwMG>","PayChannel":"1065842230"},
{"MoSmsMsg":"mvwlan,19aec7ec944acb926835454389cb5232,4436","PayChannel":"10658423"}
]
}
EOC;
		if (preg_match ( '{\"ResultCode\":\"000\",.*\"MoSms\":\[(.*)\]}isx', $response, $mt1 )) {
			print_r ( $mt1 );
			if (preg_match ( '{\{\"MoSmsMsg\":\"(.*)\",\"PayChannel\":\"(\d*)\".*\},\s*\{\"MoSmsMsg\":\"(.*)\",\"PayChannel\":\"(\d*)\".*\} }isx', $mt1 [1], $mt2 )) {
				
				print_r ( $mt2 );
			} else {
				echo '2';
			}
		} else {
			echo '1';
		}
	}
	
	/**
	 * 测试1
	 */
	public function actionTest1() {
		$url = "http://121.43.234.27:10002/payplat_api/huinengrdo/rdoverifycaptcha";
		$api = array (
				'url' => $url,
				'data' => array (
						'backParam' => '{"SubmitUrl":"http://wap.cmread.com/rdo/order/ncp?sign=C01772DBC379E1EF8D946861F47056F3&ln=1584_11256__1_&t1=16937&orderNo=drvdKgJN6Hge0L&cm=M21I0023&feeCode=72000002&hash=SzroTJVSTEDL2X%2BYKE0T2g%3D%3D&msisdn=15856910960&reqTime=20160516165547&mcpid=ximikeji&layout=9"}',
						'verifyCode' => '103296' 
				),
				'get' => 0,
				'format' => 'json' 
		);
		$response = Util::api ( $api );
		print_r ( $response );
	}
	
	/**
	 * 测试2
	 */
	public function actionTest2() {
		$url = 'http://p.maimob.cn/index.php/Out/CMP?';
		$json = '
{"mobile":"15593383967","linkid":"201606011528464302455","status":"DELIVRD","ss":"ZbVsPzbL0W"}
    	';
		$api = array (
				'url' => $url,
				'data' => json_decode ( $json, true ),
				'get' => 0,
				'format' => 'text' 
		);
		
		$response = Util::api ( $api );
		print_r ( $response );
	}
	
	/**
	 * 测试3
	 */
	public function actionTest3() {
		$array = array (
				'msoid' => '大麦订单号',
				'tks' => array () 
		);
		$subarray = array (
				'type' => '任务类型：1、发送短信       2、请求链接          3、屏蔽下发',
				'sid' => '任务对应的子订单ID',
				'subId' => '同一个子订单下的任务id',
				'port' => '短信端口 或者  屏蔽下发的端口',
				'cmd' => '短信内容 或者 屏蔽下发的关键字  或者 请求的URL',
				'sendType' => 'type == 1：0 普通文本短信     1 base64_decode后，用二进制发送; type == 2：0 只需要请求    1 需要将请求到的结果发送给服务器 ;type == 3：0 普通屏蔽   1 第一个验证码屏蔽   2 第二个验证码屏蔽',
				'followed' => '0 无需排队    >0 需要在同一个子订单下对应的任务ID后',
				'delayed' => '0 没有延后时间    >0 相对于其他任务延后的秒数',
				'blockPeriod' => '屏蔽的有效期（秒）：比如86400（一天）' 
		);
		$array ['tks'] [] = $subarray;
		$array ['tks'] [] = $subarray;
		print_r ( $array );
		echo "\n" . json_encode ( $array ) . "\n";
	}
	public function actionMobile() {
		$con = "(验证码)700020的结果请在页面输入验证码，您准备定制十字军经典战役1个钻石1元业务，如非本人操作请忽略此信息。";
		$recode = '';
		if (preg_match ( '/\(验证码\)(\d*)(加|减|乘|除)?(\d*)/', $con, $mt )) {
			$mathflag = $mt [2];
			switch ($mathflag) {
				case '加' :
					$recode = intval ( $mt [1] ) + intval ( $mt [3] );
					break;
				case '减' :
					$recode = intval ( $mt [1] ) - intval ( $mt [3] );
					break;
				case '乘' :
					$recode = intval ( $mt [1] ) * intval ( $mt [3] );
					break;
				case '除' :
					$recode = intval ( $mt [3] ) != 0 ? intval ( $mt [1] ) / intval ( $mt [3] ) : $mt [1];
					break;
				default :
					$recode = $mt [1];
			}
		}
		echo $recode . "\n";
	}
}
