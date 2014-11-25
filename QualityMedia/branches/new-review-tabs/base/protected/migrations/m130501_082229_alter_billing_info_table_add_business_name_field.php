<?php
/**
 * Add "businessName" field to "billing_info" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130501_082229_alter_billing_info_table_add_business_name_field extends CDbMigration
{
    public $tableName = 'billing_info';

    public $columnName = 'companyName';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'VARCHAR(250) NOT NULL COLLATE utf8_general_ci AFTER `lastName`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}