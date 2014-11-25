<?php
/**
 * Add "postingStatus" field to "messages" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m140119_221749_alter_messages_add_posting_status_field extends CDbMigration
{
    public $tableName = 'messages';

    public $columnName = 'postingStatus';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `sent`');

        //Initially set the existing approved reviews as sent
        $approvalStatuses = implode(', ', array(Message::APPROVAL_STATUS_ACCEPTED, Message::APPROVAL_STATUS_CHANGED));

        //Construct update query
        $updateQuery = "UPDATE {$this->tableName} m
        LEFT JOIN worker_active_tasks w
            ON w.taskName = '%s' AND w.data LIKE CONCAT('{\"id\":\"', m.id, '\"%%}')
        SET {$this->columnName} = 1
        WHERE
            m.sent =1
            AND %s IN({$approvalStatuses})
            AND w.id IS NOT NULL
            AND w.status = 1
        ";

        //Update public comments
        $this->execute(sprintf($updateQuery, 'PhantomJsPrivateMessageWorker', 'm.approvalStatus'));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}