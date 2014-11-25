<?php
/**
 * Add Junior AM relatedfields into "reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m140125_164624_alter_reviews_add_total_approval_status_field extends CDbMigration
{
    public $tableName = 'reviews';
    public $columnName = 'totalApprovalStatus';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TINYINT(1) UNSIGNED NOT NULL DEFAULT '.Review::APPROVAL_STATUS_OPEN.' AFTER `approvalStatus`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}