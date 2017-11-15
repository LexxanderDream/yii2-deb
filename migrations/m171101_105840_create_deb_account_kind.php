<?php

use yii\db\Migration;

/**
 * Handles the creation of table `account_kind`.
 */
class m171101_105840_create_deb_account_kind extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%deb_account_kind}}', [
            'id'     => $this->primaryKey(),
            'name'   => $this->string(),
            'entity' => $this->string(),
        ], $tableOptions);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drop table
        $this->dropTable('{{%deb_account_kind}}');
    }
}
