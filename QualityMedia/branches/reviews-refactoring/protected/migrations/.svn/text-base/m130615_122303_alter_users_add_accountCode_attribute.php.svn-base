<?php
/**
 * Add "accountCode" column into "users" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130615_122303_alter_users_add_accountCode_attribute extends CDbMigration
{
    public $tableName = 'users';

    public $columnName = 'accountCode';

    public function safeUp()
    {
        $this->addColumn($this->tableName, $this->columnName, 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci AFTER `accountManagerId`');
        $this->update($this->tableName, array($this->columnName=>new CDbExpression('email')));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}