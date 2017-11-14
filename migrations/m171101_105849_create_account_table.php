<?php

use yii\db\Migration;

/**
 * Handles the creation of table `account`.
 */
class m171101_105849_create_account_table extends Migration
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

        $this->createTable('{{%deb_account}}', [
            'id'         => $this->primaryKey(),
            'kind_id'    => $this->integer()->notNull(),
            'type'       => $this->integer()->notNull(),
            'owner_id'   => $this->integer(),
            'amount'     => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
        ], $tableOptions);


        // create index for column `kind_id`
        $this->createIndex(
            'idx-deb_account-kind_id',
            '{{%deb_account}}',
            'kind_id'
        );

        // add foreign key for table `billing_account`
        $this->addForeignKey(
            'fk-deb_account-kind_id',
            '{{%deb_account}}',
            'kind_id',
            '{{%deb_account_kind}}',
            'id',
            'CASCADE'
        );


        // creates index for column `kind-owner_id`
        $this->createIndex(
            'idx-deb_account-kind__owner_id',
            '{{%deb_account}}',
            ['kind_id', 'owner_id']
        );

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `account_kind`
        $this->dropForeignKey(
            'fk-deb_account-kind_id',
            '{{%deb_account}}'
        );

        // drops index for column `kind_id`
        $this->dropIndex(
            'idx-deb_account-kind_id',
            '{{%deb_account}}'
        );

        // drops index for column `kind-ownerId`
        $this->dropIndex(
            'idx-deb_account-kind__owner_id',
            '{{%deb_account}}'
        );

        // drops table
        $this->dropTable('{{%deb_account}}');
    }
}
