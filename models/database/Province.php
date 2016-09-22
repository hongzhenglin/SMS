<?php

namespace app\models\database;

use Yii;

/**
 * This is the model class for table "province".
 *
 * @property integer $id
 * @property string $name
 * @property integer $cmcc
 * @property integer $cuc
 * @property integer $cnc
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cmcc', 'cuc', 'cnc'], 'integer'],
            [['name'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'cmcc' => 'Cmcc',
            'cuc' => 'Cuc',
            'cnc' => 'Cnc',
        ];
    }
}
