<?php

namespace app\models\database;

use Yii;

/**
 * This is the model class for table "province".
 *
 * @property integer $id
 * @property string $name
 */
class Province extends \yii\db\ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'province';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [ 
				[ 
						[ 
								'name' 
						],
						'string',
						'max' => 150 
				] 
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [ 
				'id' => 'ID',
				'name' => 'Name' 
		];
	}
	
	/**
	 * @inheritdoc
	 *
	 * @return ProvinceQuery the active query used by this AR class.
	 */
	public static function find() {
		return new ProvinceQuery ( get_called_class () );
	}
	
	/* 获取省份的名字 */
	public static function getNameById($id) {
		$cacheInfo = self::getAllCache ();
		return $cacheInfo && isset ( $cacheInfo [$id] ) ? $cacheInfo [$id] : '';
	}
	
	/* 获取省份的cache信息 */
	public static function getAllCache() {
		$res = array ();
		try {
			$cacheKey = 'MIIPAY_PROVINCE_LIST';
			$cacheInfo = Yii::$app->cache->get ( $cacheKey );
			$res = json_decode ( $cacheInfo, TRUE );
			if (! $res) {
				$msProvince = self::findAll ();
				foreach ( $msProvince as $province ) {
					$res [$province->id] = $province->name;
				}
				Yii::$app->cache->set ( $cacheKey, json_encode ( $res ) );
			}
		} catch ( Exception $e ) {
		}
		return $res;
	}
}
