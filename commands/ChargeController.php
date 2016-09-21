<?php

namespace app\commands;

use yii;
use app\models\database\MSCharge;
class ChargeController extends \yii\console\Controller {
	public function actionIndex() {
		$data = MSCharge::getAllProvince();
		print_r($data);
		echo "\n=== \n";
		// return $this->render('index');
	}
	
	/**
	 * 建立数据
	 */
	public function actionBulid(){
		$db = Yii::$app->db;
		$sql = "SELECT distinct provider,province FROM simCard WHERE mobile != '' AND mobile != '0' AND provider > 0 AND province > 0 AND level = 1";
		$simCards = $db->createCommand ( $sql )->queryAll ();
		if ($simCards){
			$transaction =  $db->beginTransaction();
			try {
			$cleanSql = "UPDATE province SET cmcc = 0,cuc = 0,cnc = 0 WHERE id > 0 ";
			$db->createCommand($cleanSql)->execute();
			$updateSql = "";
			foreach ( $simCards as $key => $simCard ) {
				$province = $simCard['province'];
				$provider = $simCard['provider'];
				switch ($provider){
					case 1 : $providerType = 'cmcc';break;
					case 2 : $providerType = 'cuc';break;
					case 3 : $providerType = 'cnc';break;
				}
				$updateSql .= " UPDATE province SET {$providerType} = 1 WHERE id = {$province} ; ";
				unset($simCards[$key]);
			}
			$db->createCommand($updateSql)->execute();
			$transaction->commit();
			} catch (Exception $e) {
				$transaction->rollBack();
				throw $e;
			}
		}			
			
		
	}
}
