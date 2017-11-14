<?php

namespace lexxanderdream\deb;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "account".
 *
 * @property integer $id
 * @property integer $kind_id
 * @property integer $type
 * @property integer $owner_id
 * @property integer $amount
 * @property string $created_at
 *
 * @property AccountKind $kind
 * @property ActiveRecord $owner
 * @property Operation[] $operations
 */
class Account extends \yii\db\ActiveRecord
{
    // Account types
    const TYPE_MAIN = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deb_account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kind_id', 'type'], 'required'],
            [['kind_id', 'type', 'owner_id', 'amount'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'kind_id'    => 'Kind ID',
            'type'       => 'Type',
            'owner_id'   => 'Owner ID',
            'amount'     => 'Amount',
            'created_at' => 'Created At',
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
     * @return \yii\db\ActiveRecord
     */
    public function getOwner()
    {
        $className = $this->kind->entity;
        $model = $className::findOne($this->owner_id);

        return $model;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperations()
    {
        return $this->hasMany(Operation::className(), ['account_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKind()
    {
        return $this->hasOne(AccountKind::className(), ['id' => 'kind_id']);
    }

    /**
     * @param string $entity
     * @param int $entityId
     * @param int $type
     * @return Account
     */
    public static function get($entity = null, $entityId = null, $type = self::TYPE_MAIN)
    {
        if (!$kind = AccountKind::findOne(['entity' => $entity])) {
            $kind = AccountKind::create($entity);
        }

        if (!$account = Account::findOne(['kind_id' => $kind->id, 'owner_id' => $entityId])) {
            $account = Account::create($kind->id, $type, $entityId);
        }

        return $account;
    }

    /**
     * @param integer $kindId
     * @param integer $type
     * @param integer $ownerId
     * @return Account
     * @throws ServerErrorHttpException
     */
    public static function create($kindId, $type, $ownerId = null)
    {
        $model = new self();
        $model->kind_id = $kindId;
        $model->type = $type;
        $model->owner_id = $ownerId;
        $model->amount = 0;

        if (!$model->save())
            throw new ServerErrorHttpException('Failed to create BillingAccount');

        return $model;
    }


    /**
     * @return array
     */
    public function fields()
    {
        return [
            'amount',
        ];
    }
}
