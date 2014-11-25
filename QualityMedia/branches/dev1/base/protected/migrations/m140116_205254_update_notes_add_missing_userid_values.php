<?php
Yii::import('application.modules.am.models.Note');

/**
 * Fills missing userid values for the reviews related notes
 *
 * @author Dawid Majewski <dawid@qualitymedia.com
 */
class m140116_205254_update_notes_add_missing_userid_values extends CDbMigration
{
    public $table = 'notes';

    public function up()
    {
        $updateQuery = "UPDATE ".$this->table." n
        LEFT JOIN reviews r ON n.reviewId = r.id
        SET n.userId = r.businessId
        WHERE n.type = '".Note::TYPE_REVIEW."' AND n.reviewId IS NOT NULL";
        $this->execute($updateQuery);
    }

    public function down()
    {
        $updateQuery = "UPDATE ".$this->table." n
        SET n.userId = NULL
        WHERE n.type = '".Note::TYPE_REVIEW."'";
        $this->execute($updateQuery);
    }
}