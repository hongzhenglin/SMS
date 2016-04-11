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
    	$url = 'http://local.pluto.com/index.php/Out/CMP/ss/3subR6msNi?spcode=1066666666&mobile=18740207122&linkid=12321634124321313&mo=342111&status=DELIVRD';
     	$response = Util::getRequest($url);
// 	   	$expression = json_decode($response,true);
//     	print_r($expression);
echo $response;
    
    	echo "\n end \n";
//      $json = '\u8d44\u8d39\u683c\u5f0f\u9519\u8bef';
//      	$pc2 = Util::unicode_decode($json);
// 		echo  $pc2 . "\n";
    }
    
    public function actionTest(){
    	$url = 'http://local.pluto.com/index.php/Out/CMP?';
    	$json = '{"cpparam":"313WRLsvWG3FT","status":"0","linkid":"f7addf9f6e10445e5fc7fc24280b979d","msg":"mghyjrsw99z3ZV","fee":"4","ss":"NnedVTtSjA"}';
		$api = array(
					'url' 		=> $url,
					'data' 	=> json_decode($json,true),
					'get'		=> 1,
					'format'	=> 'text'
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
    	$url = 'http://local.pluto.com/index.php/Out/CMP/ss/WXAcnzrmKq?';
    	$json = '{"spnumber":"1194","mobile":"15856910960","linkid":"f7addf9f6e10445e5fc7fc24280b979d","msg":"mghyjrsw99z3ZV","fee":"4","ss":"WXAcnzrmKq"}';
		$api = array(
					'url' 		=> $url,
					'data' 	=> $json,
					'get'		=> 0,
					'format'	=> 'text'
			);		
		
		$response 	= Util::api($api);
    	print_r($response);
    	
    }
   
  
}
