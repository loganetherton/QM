<?php
/**
 * Add "approvalStatus" field into "messages" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m131104_162911_alter_messages_add_approval_status_field extends CDbMigration
{
    public $tableName = 'messages';

    public $columnName = 'approvalStatus';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TINYINT(1) UNSIGNED NOT NULL DEFAULT '.Message::APPROVAL_STATUS_OPEN.' AFTER `sent`');
        $this->execute("UPDATE ".$this->tableName." SET ".$this->columnName." = ".Message::APPROVAL_STATUS_ACCEPTED);
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}