<?php
/**
 * Create "system_notifications" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130904_230249_create_system_notifications_table extends CDbMigration
{
    public $tableName = 'system_notifications';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'                => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'accountManagerId'  => 'int(11) UNSIGNED NOT NULL',
            'content'           => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'url'               => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'type'              => 'smallint(1) NOT NULL',
            'status'            => 'smallint(1) NOT NULL',
            'createdAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}