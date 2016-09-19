<?php

namespace app\models\database;

/**
 * This is the ActiveQuery class for [[SimCard]].
 *
 * @see SimCard
 */
class SimCardQuery extends \yii\db\ActiveQuery {
	/*
	 * public function active()
	 * {
	 * $this->andWhere('[[status]]=1');
	 * return $this;
	 * }
	 */
	
	/**
	 * @inheritdoc
	 *
	 * @return SimCard[]|array
	 */
	public function all($db = null) {
		return parent::all ( $db );
	}
	
	/**
	 * @inheritdoc
	 *
	 * @return SimCard|array|null
	 */
	public function one($db = null) {
		return parent::one ( $db );
	}
}