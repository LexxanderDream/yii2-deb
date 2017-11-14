<?php
/**
 * Created by PhpStorm.
 * User: lexxander
 * Date: 14.11.2017
 * Time: 17:38
 */

namespace lexxanderdream\deb\behaviors;

use lexxanderdream\deb\models\Account;
use yii\base\Behavior;

class AccountableBehavior extends Behavior
{
    public $primaryKey = 'id';

    /**
     * @param int $type
     * @return Account
     */
    public function getAccount($type = Account::TYPE_MAIN)
    {
        $owner = $this->owner;
        $entity = $owner::className();

        return Account::get($entity, $owner->{$this->primaryKey}, $type);
    }
}