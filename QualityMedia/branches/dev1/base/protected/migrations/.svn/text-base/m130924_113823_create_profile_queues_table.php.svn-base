<?php
/**
 * Add "profile_queues" table to handle new businesses queue.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130924_113823_create_profile_queues_table extends CDbMigration
{
    public $tableName = 'profile_queues';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'task'          => 'VARCHAR(255) NOT NULL COLLATE utf8_general_ci',
            'params'        => 'text(5000) COLLATE utf8_general_ci',
            'status'        => 'INT(1) NOT NULL COLLATE utf8_general_ci',
            'errorReason'   => 'VARCHAR(255) NOT NULL COLLATE utf8_general_ci',
            'attempts'      => 'TINYINT(2) UNSIGNED NOT NULL DEFAULT 0',
            'createdAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}