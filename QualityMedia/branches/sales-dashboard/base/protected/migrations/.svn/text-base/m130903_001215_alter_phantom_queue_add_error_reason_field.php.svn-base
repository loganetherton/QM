<?php
/**
 * Alter "phantom_queue", update "addonsDetails" field.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130903_001215_alter_phantom_queue_add_error_reason_field extends CDbMigration
{
    public $tableName = 'phantom_queue';

    public $columnName = 'errorReason';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'varchar(255) NOT NULL DEFAULT "" AFTER status');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}