<?php
/**
 * Add "oldStatus" field to "reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m140124_205652_alter_reviews_add_old_status_field extends CDbMigration
{
    public $tableName = 'reviews';

    public $columnName = 'oldStatus';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `status`');

        //Update the field values for already replied reviews
        $updateQuery = "UPDATE {$this->tableName} r
            LEFT JOIN messages m ON r.id = m.reviewId AND r.userName != m.from
            SET r.{$this->columnName} = ".Review::STATUS_REPLIED."
            WHERE r.publicCommentContent != '' OR m.id IS NOT NULL
        ";

        $this->execute($updateQuery);
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}