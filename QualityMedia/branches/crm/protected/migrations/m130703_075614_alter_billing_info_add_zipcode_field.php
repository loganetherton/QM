<?php
/**
 * Add "zipCode" field to "billing_info" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130703_075614_alter_billing_info_add_zipcode_field extends CDbMigration
{
    public $tableName = 'billing_info';

    public $columnName = 'zipCode';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'varchar(10) NOT NULL COLLATE utf8_general_ci AFTER `city`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}