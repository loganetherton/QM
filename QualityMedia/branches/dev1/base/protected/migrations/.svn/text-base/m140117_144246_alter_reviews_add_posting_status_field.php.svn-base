<?php
/**
 * Add "postingStatus" field to "reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m140117_144246_alter_reviews_add_posting_status_field extends CDbMigration
{
    public $tableName = 'reviews';

    public $columnName = 'postingStatus';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `precontract`');

        //Initially set the existing approved reviews as sent
        $approvalStatuses = implode(', ', array(Review::APPROVAL_STATUS_ACCEPTED, Review::APPROVAL_STATUS_CHANGED));

        //Construct update query
        $updateQuery = "UPDATE {$this->tableName} r
        LEFT JOIN worker_active_tasks w
            ON w.taskName = '%s' AND w.data LIKE CONCAT('{\"id\":\"', r.id, '\"%%}')
        SET {$this->columnName} = 1
        WHERE
            r.status > 0
            AND %s IN({$approvalStatuses})
            AND w.id IS NOT NULL
            AND w.status = 1
        ";

        //Update public comments
        $this->execute(sprintf($updateQuery, 'PhantomJsPublicCommentWorker', 'r.approvalStatus'));
        //Update public flags
        $this->execute(sprintf($updateQuery, 'PhantomJsFlagReviewWorker', 'r.flagApprovalStatus'));
    }


    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}