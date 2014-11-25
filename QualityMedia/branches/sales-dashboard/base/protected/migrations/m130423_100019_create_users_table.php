<?php
/**
 * Create "users" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130423_100019_create_users_table extends CDbMigration
{
    public $tableName = 'users';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'        => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'email'     => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci',
            'password'  => 'CHAR(128) NOT NULL COLLATE utf8_general_ci',
            'salt'      => 'CHAR(128) NOT NULL COLLATE utf8_general_ci',
            'createdAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->createIndex('unique_email', $this->tableName, 'email', true);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}