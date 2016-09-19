<?php

namespace app\models\database;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\database\ChargeBill;

/**
 * chargeBillSerach represents the model behind the search form about `app\models\database\ChargeBill`.
 */
class chargeBillSerach extends ChargeBill
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'provider', 'province', 'status', 'chargeTime', 'recordTime', 'updateTime'], 'integer'],
            [['mobile', 'imsi', 'fee'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ChargeBill::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'provider' => $this->provider,
            'province' => $this->province,
            'status' => $this->status,
            'chargeTime' => $this->chargeTime,
            'recordTime' => $this->recordTime,
            'updateTime' => $this->updateTime,
        ]);

        $query->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'imsi', $this->imsi])
            ->andFilterWhere(['like', 'fee', $this->fee]);

        return $dataProvider;
    }
}
