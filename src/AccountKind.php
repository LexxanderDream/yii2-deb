<?php

namespace lexxanderdream\deb;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "account_kind".
 *
 * @property string $entity
 * @property string $name
 */
class AccountKind extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%deb_account_kind}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['entity', 'string'],
            ['name', 'string'],
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
            'name'   => 'Name',
        ];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (!empty($this->name))
            return $this->name;

        if (!empty($this->entity))
            return $this->entity;

        return 'SYSTEM';
    }

    /**
     * @param string $entity
     * @param string $name
     * @return AccountKind
     * @throws ServerErrorHttpException
     */
    public static function create($entity, $name = '')
    {
        $model = new AccountKind();
        $model->entity = $entity;
        $model->name = $name;

        if (!$model->save())
            throw new Exception('Failed to create AccountKind');

        return $model;
    }
}
