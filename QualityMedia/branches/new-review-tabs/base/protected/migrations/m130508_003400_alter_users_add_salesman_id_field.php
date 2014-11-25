<?php
/**
 * Add "salesmanId" field to "users" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130508_003400_alter_users_add_salesman_id_field extends CDbMigration
{
    public $tableName = 'users';

    public $columnName = 'salesmanId';

    public $salesmenFK = 'users_salesmen_fk';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'int(11) UNSIGNED NULL AFTER `id`');

        $this->addForeignKey($this->salesmenFK, $this->tableName, $this->columnName, 'salesmen', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->salesmenFK, $this->tableName);

        $this->dropColumn($this->tableName, $this->columnName);
    }
}