<?php
/**
 * Create "users" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130730_161309_create_phantom_queue_table extends CDbMigration
{
	public $tableName = 'phantom_queue';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'        => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'task'      => 'VARCHAR(255) NOT NULL COLLATE utf8_general_ci',
            'params'    => 'text(5000) COLLATE utf8_general_ci',
            'status'    => 'INT(1) NOT NULL COLLATE utf8_general_ci',
            'createdAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt' => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}