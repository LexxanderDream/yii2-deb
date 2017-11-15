<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transaction_type`.
 */
class m171101_105842_create_deb_transaction_type extends Migration
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

        $this->createTable('{{%deb_transaction_type}}', [
            'id'   => $this->primaryKey(),
            'name' => $this->string(),
        ], $tableOptions);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drop table
        $this->dropTable('{{%deb_transaction_type}}');
    }
}
