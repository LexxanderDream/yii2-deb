<?php

namespace lexxanderdream\deb\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "operation".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $account_id
 * @property integer $amount
 * @property integer $transaction_id
 * @property string $created_at
 *
 * @property Account $account
 * @property Transaction $transaction
 */
class Operation extends \yii\db\ActiveRecord
{
    const TYPE_INC = 1;
    const TYPE_DEC = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%operation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'account_id', 'amount', 'transaction_id'], 'integer'],
            [['account_id', 'amount', 'transaction_id'], 'required'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'type'           => 'Type',
            'account_id'     => 'Account ID',
            'amount'         => 'Amount',
            'transaction_id' => 'Transaction ID',
            'created_at'      => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value'              => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['id' => 'transaction_id']);
    }

    /**
     * @param $accountId
     * @param $type
     * @param $amount
     * @param $transactionId
     * @return Operation
     * @throws ServerErrorHttpException
     */
    public static function create($accountId, $type, $amount, $transactionId)
    {
        $model = new self();
        $model->account_id = $accountId;
        $model->type = $type;
        $model->amount = $amount;
        $model->transaction_id = $transactionId;

        if (!$model->save())
            throw new ServerErrorHttpException('Failed to create BillingOperation');

        return $model;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        $account = $this->account;

        switch ($this->type) {
            // @todo updateCounters
            case self::TYPE_DEC:
                $account->updateAttributes(['amount' => new Expression('`amount`-' . $this->amount)]);
                break;

            case self::TYPE_INC:
                $account->updateAttributes(['amount' => new Expression('`amount`+' . $this->amount)]);
                break;
        }

        parent::afterSave($insert, $changedAttributes);
    }
}
