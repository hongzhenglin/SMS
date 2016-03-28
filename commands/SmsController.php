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
    	$con = "065842230####0000807786P602\/%98562394f00c6208H####F2r85WP2{|BykN9VPfg!q]q#z5g==r+ib5tx:4gScS38T20)Rm625e05915eM000|0H00000a8XPW+1^vT+2K\/LWt\/VLKDjLOUK";
    $res = explode( '####',$con,2);
     var_dump($res);
    }
    
    public function actionTest(){
    	$url = 'http://p.maimob.cn/index.php/Out/CMP/ss/rt7Cq8bHvu?';
    	$json = '{"code":0,"msg":"计费成功","orderId":"962455","transmissionData":"0AvSBxY5Q8","mobile":"15593383967"}';
		$api = array(
					'url' 		=> $url,
					'data' 	=> $json,
					'get'		=> 0,
					'format'	=> 'text'
			);			
		
		$response 	= Util::api($api);
		
		
    	print_r($response);
    
    }
    
    public  function  actionTest1(){
		$url = "http://p.maimob.cn/index.php/API?action=GCMF&soid=8e14ihlE21r8fqcE4avmhiwp&cp=100865034910&content=尊敬的客户，您好！您将订购中国移动的天上西藏手机报业务，信息费为5.00元/月，请在24小时内回复“1” 或“是”确认订购，回复其他内容和不回复则不订购。中国移动&vmId=17&action=GCMF&msa=25dlwlAq0AC07pj4cjnaumgg&ver=2201&tp=1459146193";
		
		$api = array(
				'url' 		=> $url,
				'data' 	=> array(),
				'get'		=> 1,
				'format'	=> 'json'
		);
		
		$response 	= Util::api($api);
		
		
		print_r($response);
    }
   
  
}
