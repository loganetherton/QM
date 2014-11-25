<?php
/**
 * Alter "system_notifications" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130910_080501_alter_system_notifications_modify_type_field extends CDbMigration
{
    public $tableName = 'system_notifications';
    public $columnName = 'type';

    public function up()
    {
        $query = "ALTER TABLE {$this->tableName} MODIFY COLUMN {$this->columnName} VARCHAR(255) NOT NULL COLLATE utf8_general_ci;";

        $this->dbConnection->createCommand($query)->execute();
    }

    public function down()
    {
        $query = "ALTER TABLE {$this->tableName} MODIFY COLUMN {$this->columnName} SMALLINT(1);";
        $this->dbConnection->createCommand($query)->execute();
    }
}