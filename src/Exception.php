<?php
/**
 * Created by PhpStorm.
 * User: lexxander
 * Date: 15.11.2017
 * Time: 19:11
 */

namespace lexxanderdream\deb;

class Exception extends \yii\base\Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'DebException';
    }
}