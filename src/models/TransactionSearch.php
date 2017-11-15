<?php

namespace lexxanderdream\deb\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use lexxanderdream\deb\Transaction;

/**
 * TransactionSearch represents the model behind the search form about `lexxanderdream\deb\Transaction`.
 */
class TransactionSearch extends Transaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'sender_account_id', 'receiver_account_id', 'amount'], 'integer'],
            [['details', 'created_at'], 'safe'],
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
        $query = Transaction::find();

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
            'id'                  => $this->id,
            'type_id'             => $this->type_id,
            'sender_account_id'   => $this->sender_account_id,
            'receiver_account_id' => $this->receiver_account_id,
            'amount'              => $this->amount,
            'created_at'          => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'details', $this->details]);

        return $dataProvider;
    }
}
