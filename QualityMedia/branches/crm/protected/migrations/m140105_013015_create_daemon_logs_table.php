<?php
/**
 * Create "daemon_logs" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m140105_013015_create_daemon_logs_table extends CDbMigration
{
    public $tableName = 'daemon_logs';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'        => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'name'      => 'VARCHAR(255) NOT NULL COLLATE utf8_general_ci',
            'host'      => 'VARCHAR(255) NOT NULL COLLATE utf8_general_ci',
            'pid'       => 'INT(11) UNSIGNED NOT NULL',
            'argv'      => 'LONGTEXT NOT NULL COLLATE utf8_general_ci',
            'status'    => 'VARCHAR(8) NOT NULL COLLATE utf8_general_ci',
            'createdAt' => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt' => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}