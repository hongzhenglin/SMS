<?php

namespace app\models\database;

use yii;
use app\models\database\Province;
use app\models\database\SimCard;

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
		$cacheInfo = self::getAllProvince ();
		return $cacheInfo && isset ( $cacheInfo [$id] ) ? $cacheInfo [$id] : '';
	}
	
	/* 获取省份的cache信息 */
	public static function getAllProvince() {
		$res = array ();
		try {
			$cacheKey = 'MIIPAY_PROVINCE_LIST';
			$cacheInfo = Yii::$app->cache->get ( $cacheKey );
			$res = json_decode ( $cacheInfo, TRUE );
			if (! $res) {
				$msProvince = Province::find ()->all ();
				foreach ( $msProvince as $province ) {
					$res [$province->id] = $province->name;
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
			// if (! $res) {
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
			// }
		} catch ( Exception $e ) {
		}
		return $res;
	}
	
	/* 获取SimCard的cache信息 */
	public static function getAllMobile() {
		$res = array ();
		$simcards = self::getAllSimCard ();
		foreach ( $simcards as $simcard ) {
			if (! isset ( $simcard ['mobile'] )) {
				continue;
			}
			$res [$simcard ['mobile']] = $simcard ['mobile'];
		}
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
	 * 获取状态名称
	 */
	public static function getStatusMsg($status){
		return $status == 0 ? '失败' : '成功';
	}
}