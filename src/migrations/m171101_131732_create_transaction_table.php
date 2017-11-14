<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transaction`.
 */
class m171101_131732_create_transaction_table extends Migration
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

        $this->createTable('{{%transaction}}', [
            'id'                  => $this->primaryKey(),
            'type_id'             => $this->integer()->notNull(),
            'sender_account_id'   => $this->integer()->notNull(),
            'receiver_account_id' => $this->integer()->notNull(),
            'amount'              => $this->integer()->notNull(),
            'details'             => $this->text(),
            'created_at'          => $this->dateTime()->notNull(),
        ], $tableOptions);

        // creates index for column `type_id`
        $this->createIndex(
            'idx-deb_transaction-type_id',
            '{{%transaction}}',
            'type_id'
        );

        // add foreign key for table `transaction_type`
        $this->addForeignKey(
            'fk-deb_transaction-type_id',
            '{{%transaction}}',
            'type_id',
            '{{%transaction_type}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `sender_account_id`
        $this->createIndex(
            'idx-deb_transaction-sender_account_id',
            '{{%transaction}}',
            'sender_account_id'
        );

        // add foreign key for table `account`
        $this->addForeignKey(
            'fk-deb_transaction-sender_account_id',
            '{{%transaction}}',
            'sender_account_id',
            '{{%account}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `receiver_account_id`
        $this->createIndex(
            'idx-deb_transaction-receiver_account_id',
            '{{%transaction}}',
            'receiver_account_id'
        );

        // add foreign key for table `account`
        $this->addForeignKey(
            'fk-deb_transaction-receiver_account_id',
            '{{%transaction}}',
            'receiver_account_id',
            '{{%account}}',
            'id',
            'RESTRICT'
        );

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `transaction_type`
        $this->dropForeignKey(
            'fk-deb_transaction-type_id',
            '{{%transaction}}'
        );

        // drops index for column `type_id`
        $this->dropIndex(
            'idx-deb_transaction-type_id',
            '{{%transaction}}'
        );

        // drops foreign key for table `account`
        $this->dropForeignKey(
            'fk-deb_transaction-sender_account_id',
            '{{%transaction}}'
        );

        // drops index for column `sender_account_id`
        $this->dropIndex(
            'idx-deb_transaction-sender_account_id',
            '{{%transaction}}'
        );


        // drops foreign key for table `account`
        $this->dropForeignKey(
            'fk-deb_transaction-receiver_account_id',
            '{{%transaction}}'
        );

        // drops index for column `receiver_account_id`
        $this->dropIndex(
            'idx-deb_transaction-receiver_account_id',
            '{{%transaction}}'
        );

        $this->dropTable('{{%transaction}}');
    }
}
