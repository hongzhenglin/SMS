<?php

namespace app\models\database;

/**
 * This is the ActiveQuery class for [[ChargeBill]].
 *
 * @see ChargeBill
 */
class ChargeBillQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return ChargeBill[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ChargeBill|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}