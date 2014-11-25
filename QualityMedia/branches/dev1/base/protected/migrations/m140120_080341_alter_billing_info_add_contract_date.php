<?php
/**
 * Add "contractDate" field to "billing_info" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m140120_080341_alter_billing_info_add_contract_date extends CDbMigration
{
    public $tableName = 'billing_info';

    public $columnName = 'contractDate';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `cardType`');

        //Update the date from the `createdAt` field by default;
        $this->execute("UPDATE {$this->tableName} SET {$this->columnName} = `createdAt`");
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}