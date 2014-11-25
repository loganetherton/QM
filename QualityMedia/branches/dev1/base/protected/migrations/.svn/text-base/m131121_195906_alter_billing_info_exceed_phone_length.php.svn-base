<?php
/**
 * Exceed 'phone' field length.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m131121_195906_alter_billing_info_exceed_phone_length extends CDbMigration
{
    public $tableName = 'billing_info';

    public $columnName = 'phone';

    public function up()
    {
        $this->alterColumn($this->tableName, $this->columnName, 'VARCHAR(30) NOT NULL COLLATE utf8_general_ci');
    }

    public function down()
    {
        $this->alterColumn($this->tableName, $this->columnName, 'VARCHAR(15) NULL COLLATE utf8_general_ci');
    }
}