<?php
/**
 * Create "daemon_log_events" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m140105_013115_create_daemon_log_events_table extends CDbMigration
{
    public $tableName = 'daemon_log_events';

    public $daemonLogFK = 'daemon_log_events_daemon_logs_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'daemonLogId'   => 'INT(11) UNSIGNED NOT NULL',
            'type'          => 'VARCHAR(4) NOT NULL COLLATE utf8_general_ci',
            'message'       => 'LONGTEXT NOT NULL COLLATE utf8_general_ci',
            'createdAt'     => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->daemonLogFK, $this->tableName, 'daemonLogId', 'daemon_logs', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->daemonLogFK, $this->tableName);

        $this->dropTable($this->tableName);
    }
}