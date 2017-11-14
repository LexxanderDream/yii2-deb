<?php
/**
 * Created by PhpStorm.
 * User: lexxander
 * Date: 14.11.2017
 * Time: 18:42
 */

namespace lexxanderdream\deb\models;

use yii\db\ActiveRecord;

class ActiveRecordTransaction extends Transaction
{
    /**
     * @var string
     */
    public $entity;

    /**
     * @var mixed
     */
    public $entityId;

    /**
     * @var ActiveRecord
     */
    protected $_model;

    /**
     * ActiveRecordTransaction constructor.
     * @param ActiveRecord $model
     */
    public function __construct(ActiveRecord $model)
    {
        $this->entityId = $model->id;
        $this->entity = $model::className();
        $this->_model = $model;

        parent::__construct();
    }

    /**
     * return ActiveRecord
     */
    public function getModel()
    {
        return $this->_model;
    }


    /**
     * @param $class
     * @param array $options
     * @return mixed
     */
    public static function factory($class, $options = [])
    {
        $className = $options['entity'];
        $id = $options['entityId'];

        $model = $className::findOne($id);

        return new $class($model);
    }

}
