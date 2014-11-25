<?php
/**
 * Create "worker_active_task_history" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m140120_225609_create_worker_active_task_history_table extends CDbMigration
{
    public $tableName = 'worker_active_task_history';

    public $workerActivaTaskFK = 'worker_active_task_history_worker_active_tasks_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'        => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'taskId'    => 'INT(11) UNSIGNED NOT NULL',
            'content'   => 'VARCHAR(255) NOT NULL COLLATE utf8_general_ci',
            'createdAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->workerActivaTaskFK, $this->tableName, 'taskId', 'worker_active_tasks', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->workerActivaTaskFK, $this->tableName);

        $this->dropTable($this->tableName);
    }
}