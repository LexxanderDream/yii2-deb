<?php

namespace lexxanderdream\deb\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use lexxanderdream\deb\Operation;

/**
 * OperationSearch represents the model behind the search form about `lexxanderdream\deb\Operation`.
 */
class OperationSearch extends Operation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'account_id', 'amount', 'transaction_id'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = Operation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['created_at' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'account_id' => $this->account_id,
            'amount' => $this->amount,
            'transaction_id' => $this->transaction_id,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
