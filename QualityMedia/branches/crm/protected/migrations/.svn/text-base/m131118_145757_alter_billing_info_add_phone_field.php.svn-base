<?php
/**
 * Add "phone" field to "billing_info" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m131118_145757_alter_billing_info_add_phone_field extends CDbMigration
{
    public $tableName = 'billing_info';

    public $columnName = 'phone';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'VARCHAR(15) NULL COLLATE utf8_general_ci AFTER `country`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}