<?php

namespace app\models\database;

use Yii;

/**
 * This is the model class for table "chargeBill".
 *
 * @property integer $id
 * @property string $mobile
 * @property integer $provider
 * @property integer $province
 * @property integer $fee
 * @property integer $status
 * @property integer $chargeTime
 * @property integer $recordTime
 * @property integer $updateTime
 */
class ChargeBill extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chargeBill';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provider', 'province', 'fee', 'status'], 'integer'],
            [['mobile'], 'string', 'max' => 11],
        	[['provider', 'province', 'fee', 'mobile','chargeTime'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => '手机号',
            'provider' => '运营商',
            'province' => '省份',
            'fee' => '金额',
            'status' => '状态',
            'chargeTime' => '充值时间',
            'recordTime' => '记录时间',
            'updateTime' => '更新时间',
        ];
    }
}
