<?php
/**
 * Create "transactions" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130424_051936_create_transaction_table extends CDbMigration
{
    public $tableName = 'transactions';

    public $userFK = 'transactions_users_fk';
    public $subscriptionFK = 'transactions_subscriptions_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'userId'        => 'INT(11) UNSIGNED NOT NULL',
            'subscriptionId'=> 'INT(11) UNSIGNED NOT NULL',
            'uuid'          => 'CHAR(32) NOT NULL COLLATE utf8_general_ci',
            'action'        => 'VARCHAR(15) NOT NULL COLLATE utf8_general_ci',
            'currency'      => 'CHAR(3) NOT NULL COLLATE utf8_general_ci',
            'amountInCents' => 'MEDIUMINT(6) UNSIGNED NOT NULL',
            'taxtInCents'   => 'MEDIUMINT(6) UNSIGNED NOT NULL',
            'status'        => 'VARCHAR(10) NOT NULL COLLATE utf8_general_ci',
            'source'        => 'VARCHAR(15) NOT NULL COLLATE utf8_general_ci',
            'test'          => 'TINYINT(1) UNSIGNED NOT NULL COLLATE utf8_general_ci',
            'voidable'      => 'TINYINT(1) UNSIGNED NOT NULL COLLATE utf8_general_ci',
            'refundable'    => 'TINYINT(1) UNSIGNED NOT NULL COLLATE utf8_general_ci',
            'cvvResult'     => 'VARCHAR(50) NOT NULL COLLATE utf8_general_ci',
            'avsResult'     => 'VARCHAR(50) NOT NULL COLLATE utf8_general_ci',
            'avsResultStreet' => 'VARCHAR(50) NOT NULL COLLATE utf8_general_ci',
            'avsResultPostal' => 'VARCHAR(50) NOT NULL COLLATE utf8_general_ci',
            'transactionDate' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'createdAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->userFK, $this->tableName, 'userId', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey($this->subscriptionFK, $this->tableName, 'subscriptionId', 'subscriptions', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}