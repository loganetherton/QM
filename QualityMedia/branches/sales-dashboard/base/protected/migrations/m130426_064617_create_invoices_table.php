<?php
/**
 * Create invoices table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130426_064617_create_invoices_table extends CDbMigration
{
    public $tableName = 'invoices';

    public $transactionFK = 'invoices_transactions_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'transactionId' => 'INT(11) UNSIGNED NOT NULL',
            'uuid'          => 'CHAR(32) NOT NULL COLLATE utf8_general_ci',
            'number'        => 'INT(11) UNSIGNED NOT NULL',
            'state'         => 'VARCHAR(20) NOT NULL COLLATE utf8_general_ci',
            'subtotal'      => 'INT(11) NOT NULL',
            'total'         => 'INT(11) NOT NULL',
            'createdAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->transactionFK, $this->tableName, 'transactionId', 'transactions', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}