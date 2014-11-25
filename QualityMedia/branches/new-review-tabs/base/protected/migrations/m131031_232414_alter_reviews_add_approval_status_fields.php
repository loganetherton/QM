<?php
/**
 * Add Junior AM relatedfields into "reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m131031_232414_alter_reviews_add_approval_status_fields extends CDbMigration
{
    public $tableName = 'reviews';

    public $approvalStatus         = 'approvalStatus';
    public $flagapprovalStatus     = 'flagApprovalStatus';
    public $flagReasonCategory     = 'flagReasonCategory';
    public $seniorAmNote           = 'seniorAmNote';
    public $seniorAmNoteUpdateDate = 'seniorAmNoteUpdateDate';

    public function up()
    {
        $this->addColumn($this->tableName, $this->approvalStatus, 'TINYINT(1) UNSIGNED NOT NULL DEFAULT '.Review::APPROVAL_STATUS_OPEN.' AFTER `status`');
        $this->execute("UPDATE ".$this->tableName." SET ".$this->approvalStatus." = ".Review::APPROVAL_STATUS_ACCEPTED." WHERE status IN(".Review::STATUS_REPLIED.", ".Review::STATUS_FLAGGED.", ".Review::STATUS_ARCHIVED.")");

        $this->addColumn($this->tableName, $this->flagapprovalStatus, 'TINYINT(1) UNSIGNED NOT NULL DEFAULT '.Review::APPROVAL_STATUS_OPEN.' AFTER `'.$this->approvalStatus.'`');
        $this->execute("UPDATE ".$this->tableName." SET ".$this->flagapprovalStatus." = ".Review::APPROVAL_STATUS_ACCEPTED." WHERE status = ".Review::STATUS_FLAGGED);

        $this->addColumn($this->tableName, $this->flagReasonCategory, 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `flagReason`');

        $this->addColumn($this->tableName, $this->seniorAmNote, 'text NOT NULL AFTER latestMessageDate');
        $this->addColumn($this->tableName, $this->seniorAmNoteUpdateDate, 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `'.$this->seniorAmNote.'`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->approvalStatus);
        $this->dropColumn($this->tableName, $this->flagapprovalStatus);
        $this->dropColumn($this->tableName, $this->flagReasonCagetory);
        $this->dropColumn($this->tableName, $this->seniorAmNote);
        $this->dropColumn($this->tableName, $this->seniorAmNoteUpdateDate);
    }
}