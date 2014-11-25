<?php
/**
 * Add "lastVisit" field to "users" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130429_000723_alter_users_add_last_visit_field extends CDbMigration
{
    public $tableName = 'users';

    public $columnName = 'lastVisit';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `salt`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}