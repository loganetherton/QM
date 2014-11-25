<?php
/**
 * Add "status" field to "users" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m140102_225636_alter_users_add_status_field extends CDbMigration
{
    public $tableName = 'users';

    public $columnName = 'status';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 AFTER `salt`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}