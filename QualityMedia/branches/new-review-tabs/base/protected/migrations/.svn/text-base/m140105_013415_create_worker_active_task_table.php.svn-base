<?php
/**
 * Create "worker_active_tasks" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m140105_013415_create_worker_active_task_table extends CDbMigration
{
    public $tableName = 'worker_active_tasks';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'taskName'      => 'VARCHAR(255) NOT NULL COLLATE utf8_general_ci',
            'data'          => 'LONGTEXT NOT NULL COLLATE utf8_general_ci',
            'leaseOwner'    => 'VARCHAR(255) NOT NULL COLLATE utf8_general_ci',
            'leaseExpires'  => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'failureCount'  => 'INT(11) UNSIGNED NOT NULL DEFAULT 0',
            'failureTime'   => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'status'        => 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 0',
            'duration'      => 'BIGINT(20) UNSIGNED NOT NULL DEFAULT 0',
            'createdAt'     => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}