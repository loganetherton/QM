<?php
/**
 * Create "billing_info" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130423_102015_create_billing_info_table extends CDbMigration
{
    public $tableName = 'billing_info';

    public $userFK = 'billing_info_users_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'userId'        => 'INT(11) UNSIGNED NOT NULL',
            'firstName'     => 'VARCHAR(100) NOT NULL COLLATE utf8_general_ci',
            'lastName'      => 'VARCHAR(100) NOT NULL COLLATE utf8_general_ci',
            'address1'      => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci',
            'address2'      => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci',
            'city'          => 'VARCHAR(100) NOT NULL COLLATE utf8_general_ci',
            'state'         => 'VARCHAR(100) NOT NULL COLLATE utf8_general_ci',
            'country'       => 'CHAR(2) NOT NULL COLLATE utf8_general_ci',
            'ipAddress'     => 'VARCHAR(15) NOT NULL COLLATE utf8_general_ci',
            'last4digits'   => 'SMALLINT(4) UNSIGNED ZEROFILL NOT NULL',
            'cardType'      => 'VARCHAR(20) NOT NULL COLLATE utf8_general_ci',
            'createdAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->userFK, $this->tableName, 'userId', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}