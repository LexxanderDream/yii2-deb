<?php
/**
 * Created by PhpStorm.
 * User: lexxander
 * Date: 14.11.2017
 * Time: 17:38
 */

namespace lexxanderdream\deb;

use yii\base\Behavior;

class AccountableBehavior extends Behavior
{
    /**
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * @var callable
     */
    public $name;

    /**
     * @param int $type
     * @return Account
     */
    public function getAccount($type = Account::TYPE_MAIN)
    {
        $owner = $this->owner;
        $entity = $owner::className();
        $primaryKey = $owner::primaryKey();


        return Account::get($entity, $owner->{$primaryKey[0]}, $type);
    }

    /**
     * @return string
     */
    public function getAccountName()
    {
        $owner = $this->owner;
        $primaryKey = $owner::primaryKey();

        if (!$this->name)
            return $this->owner->{$primaryKey[0]};

        return call_user_func($this->name);
    }
}