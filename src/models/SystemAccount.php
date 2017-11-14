<?php
/**
 * Created by PhpStorm.
 * User: lexxander
 * Date: 14.11.2017
 * Time: 19:54
 */


namespace lexxanderdream\deb\models;

use lexxanderdream\deb\models\Account;

class SystemAccount extends Account
{
    public static function getInstance($type = self::TYPE_MAIN)
    {
        return Account::get(SystemAccount::className(), null, $type);
    }
}