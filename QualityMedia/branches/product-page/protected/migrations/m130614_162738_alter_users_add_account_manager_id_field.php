<?php
/**
 * Add "AccountManagerId" field to "users" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130614_162738_alter_users_add_account_manager_id_field extends CDbMigration
{
    public $tableName = 'users';

    public $columnName = 'accountManagerId';

    public $accountMangerFK = 'users_account_managers_fk';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'int(11) UNSIGNED NULL AFTER `salesmanId`');

        $this->addForeignKey($this->accountMangerFK, $this->tableName, $this->columnName, 'account_managers', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->accountMangerFK, $this->tableName);

        $this->dropColumn($this->tableName, $this->columnName);
    }
}