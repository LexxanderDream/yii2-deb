<?php

namespace lexxanderdream\deb;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\Json;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "deb_transaction".
 *
 * @property integer $id
 * @property integer $type_id
 * @property integer $sender_account_id
 * @property integer $receiver_account_id
 * @property integer $amount
 * @property string $details
 * @property string $created_at
 *
 * @property Account $receiverAccount
 * @property Account $senderAccount
 * @property TransactionType $type
 * @property Operation[] $operations
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deb_transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_account_id', 'receiver_account_id', 'amount'], 'required'],
            [['type_id', 'sender_account_id', 'receiver_account_id', 'amount'], 'integer'],
            [['details'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => 'ID',
            'type_id'             => 'Type ID',
            'sender_account_id'   => 'Sender Account ID',
            'receiver_account_id' => 'Receiver Account ID',
            'amount'              => 'Amount',
            'details'             => 'Details',
            'created_at'          => 'Created At',
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
     * @return bool
     */

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $type = TransactionType::get(self::className());

            $this->type_id = $type->id;
            $this->details = Json::encode(Yii::getObjectVars($this));
        }

        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * @param array $row
     * @return Transaction
     */
    public static function instantiate($row)
    {
        $typeId = $row['type_id'];
        $type = TransactionType::findOne($typeId);
        $className = $type->name;

        return $className::factory($className, Json::decode($row['details'], true));
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(TransactionType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperations()
    {
        return $this->hasMany(Operation::className(), ['transaction_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiverAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'receiver_account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSenderAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'sender_account_id']);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        Operation::create($this->sender_account_id, Operation::TYPE_DEC, $this->amount, $this->id);
        Operation::create($this->receiver_account_id, Operation::TYPE_INC, $this->amount, $this->id);

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param Account $senderAccount
     * @param Account $receiverAccount
     * @param $amount
     * @return Transaction
     * @throws ServerErrorHttpException
     */
    public function exec(Account $senderAccount, Account $receiverAccount, $amount)
    {
        $this->sender_account_id = $senderAccount->id;
        $this->receiver_account_id = $receiverAccount->id;
        $this->amount = $amount;

        if (!$this->save()) {
            throw new ServerErrorHttpException('Failed to create Transaction');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (defined('static::TITLE'))
            return constant('static::TITLE');

        return $this->type->name;
    }

    /**
     * @param $class
     * @param $options
     * @return mixed
     */
    public static function factory($class, $options)
    {
        return new $class($options);
    }
}
