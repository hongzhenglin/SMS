<?php

namespace app\models\database;

use yii;
use app\models\database\Province;
use app\models\database\SimCard;
use yii\helpers\ArrayHelper;

class MSCharge {
	
	/* 获取运营商的名字 */
	public static function getProviderNameById($id) {
		$cacheInfo = self::getAllProvider ();
		return $cacheInfo && isset ( $cacheInfo [$id] ) ? $cacheInfo [$id] : '';
	}
	
	/**
	 * 获取所有运营商
	 */
	public static function getAllProvider() {
		return [ 
				1 => "移动",
				2 => "联通",
				3 => "电信" 
		];
	}
	
	/* 获取省份的名字 */
	public static function getProvinceNameById($id) {
		$cacheInfo = self::getAllProvinceByProvider ();
		return $cacheInfo && isset ( $cacheInfo [$id] ) ? $cacheInfo [$id] : '';
	}
	
	/* 获取省份的cache信息 */
	public static function getAllProvinceByProvider($provider = 1 ) {
		$res = array ();
		$provinces = self::getALlProvinceInfo ();
		$com = [1=>'cmcc',2=>'cuc',3 => 'cnc'];
		foreach ( $provinces as $province ) {					 
			if ($provider == 0 || ( $provider && $province[$com[$provider]] == 1) ){
				$res [$province ['id']] =  $province ['name'];
			}			
		}
		return $res;
	}
	
	/**
	 * 获取所有省份信息
	 * @return mixed|unknown[]
	 */
	public static function getAllProvinceInfo(){
		$res = array ();
		try {
			$cacheKey = 'MIIPAY_PROVINCE_LIST';
			$cacheInfo = Yii::$app->cache->get ( $cacheKey );
			$res = json_decode ( $cacheInfo, TRUE );
			if (true || ! $res) {
				$msProvince = Yii::$app->db->createCommand ( "SELECT * FROM province WHERE id > 0 ORDER BY name desc " )->queryAll ();
				foreach ( $msProvince as $province ) {
					$res [$province ['id']] = [
							'id' => $province ['id'],
							'name' => $province ['name'],
							'cmcc' => $province ['cmcc'],
							'cuc' => $province ['cuc'],
							'cnc' => $province ['cnc']
					];
				}
				Yii::$app->cache->set ( $cacheKey, json_encode ( $res ) );
			}
		} catch ( Exception $e ) {
		}
		return $res;
	}
	
	/* 获取SimCard的cache信息 */
	public static function getAllSimCard() {
		$res = array ();
		try {
			$cacheKey = 'MIIPAY_SimCard_LIST';
			$cacheInfo = Yii::$app->cache->get ( $cacheKey );
			// $res = json_decode ( $cacheInfo, TRUE );
			 if (true || ! $res) {
				$simCards = Yii::$app->db->createCommand ( "SELECT id,mobile,imsi,provider,province FROM simCard WHERE mobile !='' AND mobile != 0" )->queryAll ();
				foreach ( $simCards as $simCard ) {
					$res [$simCard ['id']] = [ 
							'id' => $simCard ['id'],
							'mobile' => $simCard ['mobile'],
							'provider' => $simCard ['provider'],
							'province' => $simCard ['province'] 
					];
				}
				Yii::$app->cache->set ( $cacheKey, json_encode ( $res ) );
			 }
		} catch ( Exception $e ) {
		}
		return $res;
	}
	
	/* 获取SimCard的cache信息 */
	public static function getAllMobile() {
		$res = array ();
		$simcards = self::getAllSimCard ();
		$res = ArrayHelper::map($simcards, 'mobile', 'mobile');
		sort($res);
		return $res;
	}
	
	/* 获取SimCard的cache信息 */
	public static function getMobile($id) {
		$res = array ();
		$simcards = self::getAllSimCard ();
		$res = isset ( $simcards [$id] ) ? $simcards [$id] : array ();
		return $res;
	}
	
	/**
	 * 获取所有状态名称
	 */
	public static function getAllStatusMsg() {
		return [
				0 => "失败",
				1 => "成功",
		];
	}
	
	/**
	 * 获取状态名称
	 */
	public static function getStatusMsg($id){
		$cacheInfo = self::getAllStatusMsg ();
		return $cacheInfo && isset ( $cacheInfo [$id] ) ? $cacheInfo [$id] : '';
	}
}