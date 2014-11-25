<?php
/**
 * Add accountManagerId field into "messages" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m131126_155353_alter_messages_add_account_manager_id_field extends CDbMigration
{
    public $tableName = 'messages';
    public $columnName = 'accountManagerId';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'INT(11) NULL AFTER `userId`');

        $updateQuery = "UPDATE ".$this->tableName." m, reviews r, users u
            SET m.".$this->columnName." = u.accountManagerId
            WHERE m.reviewId = r.id
            AND r.businessId = u.id
            AND m.source = ".Message::SOURCE_DASHBOARD;
        $this->execute($updateQuery);
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}