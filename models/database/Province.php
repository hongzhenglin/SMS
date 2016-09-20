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
}
