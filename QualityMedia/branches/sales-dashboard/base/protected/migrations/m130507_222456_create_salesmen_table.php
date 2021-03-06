<?php
/**
 * Create "salesmen" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130507_222456_create_salesmen_table extends CDbMigration
{
    public $tableName = 'salesmen';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'        => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'username'  => 'varchar(100) NOT NULL COLLATE utf8_general_ci',
            'password'  => 'char(128) NOT NULL COLLATE utf8_general_ci',
            'salt'      => 'char(128) NOT NULL COLLATE utf8_general_ci',
            'lastVisit' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'createdAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}