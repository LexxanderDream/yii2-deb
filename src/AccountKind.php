<?php

namespace lexxanderdream\deb;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "account_kind".
 *
 * @property string $entity
 */
class AccountKind extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_kind}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['entity', 'required'],
            ['entity', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'     => 'ID',
            'entity' => 'Entity',
        ];
    }

    /**
     * @param $entity
     * @return AccountKind
     * @throws ServerErrorHttpException
     */
    public static function create($entity)
    {
        $model = new AccountKind();
        $model->entity = $entity;

        if (!$model->save())
            throw new ServerErrorHttpException('Failed to create AccountKind');

        return $model;
    }
}
