<?php

use yii\db\Migration;

/**
 * Handles the creation of table `operation`.
 */
class m171101_132446_create_operation_table extends Migration
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

        $this->createTable('{{%operation}}', [
            'id'             => $this->primaryKey(),
            'type'           => $this->smallInteger(),
            'account_id'     => $this->integer()->notNull(),
            'amount'         => $this->integer()->notNull(),
            'transaction_id' => $this->integer()->notNull(),
            'created_at'      => $this->dateTime()->notNull(),
        ], $tableOptions);

        // creates index for column `account_id`
        $this->createIndex(
            'idx-deb_operation-account_id',
            '{{%operation}}',
            'account_id'
        );

        // creates index for column `transaction_id`
        $this->createIndex(
            'idx-deb_operation-transaction_id',
            '{{%operation}}',
            'transaction_id'
        );

        // add foreign key for table `account`
        $this->addForeignKey(
            'fk-deb_operation-account_id',
            '{{%operation}}',
            'account_id',
            '{{account}}',
            'id',
            'CASCADE'
        );

        // add foreign key for table `transaction`
        $this->addForeignKey(
            'fk-deb_operation-transaction_id',
            '{{%operation}}',
            'transaction_id',
            '{{%transaction}}',
            'id',
            'CASCADE'
        );


    }

    /**
     * @inheritdoc
     */
    public function down()
    {

        // drops foreign key for table `account`
        $this->dropForeignKey(
            'fk-deb_operation-account_id',
            '{{%operation}}'
        );

        // drops index for column `sender_account_id`
        $this->dropIndex(
            'idx-deb_operation-account_id',
            '{{%operation}}'
        );

        // drops foreign key for table `billing_transaction`
        $this->dropForeignKey(
            'fk-deb_operation-transaction_id',
            '{{%operation}}'
        );

        // drops index for column `transaction_id`
        $this->dropIndex(
            'idx-deb_operation-transaction_id',
            '{{%operation}}'
        );

        $this->dropTable('{{%operation}}');
    }
}
