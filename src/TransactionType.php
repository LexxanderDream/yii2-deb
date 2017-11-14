<?php

namespace lexxanderdream\deb;

use Yii;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "transaction_type".
 *
 * @property integer $id
 * @property string $name
 */
class TransactionType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             ['name', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'   => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @param string $name
     * @return TransactionType
     */
    public static function get($name)
    {
        if (!$model = TransactionType::findOne(['name' => $name])) {
            $model = TransactionType::create($name);
        }

        return $model;
    }

    /**
     * @param string $name
     * @return TransactionType
     * @throws ServerErrorHttpException
     */
    public static function create($name)
    {
        $model = new TransactionType();
        $model->name = $name;

        if (!$model->save())
            throw new ServerErrorHttpException('Failed to create TransactionType');

        return $model;
    }
}
