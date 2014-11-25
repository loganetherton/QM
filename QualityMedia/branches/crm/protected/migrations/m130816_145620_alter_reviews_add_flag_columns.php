<?php
/**
 * Add flagging related columns to "reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130816_145620_alter_reviews_add_flag_columns extends CDbMigration
{
    public $tableName = 'reviews';

    public $flagReasonColumn = 'flagReason';
    public $flaggedAtColumn = 'flaggedAt';

    public function up()
    {
        $this->addColumn($this->tableName, $this->flagReasonColumn, 'TEXT(5000) NOT NULL COLLATE utf8_general_ci AFTER `publicCommentDate`');
        $this->addColumn($this->tableName, $this->flaggedAtColumn, 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `flagReason`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->flagReasonColumn);
        $this->dropColumn($this->tableName, $this->flaggedAtColumn);
    }
}