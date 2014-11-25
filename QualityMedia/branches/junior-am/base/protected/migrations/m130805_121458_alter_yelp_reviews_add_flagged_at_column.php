<?php
/**
 * Add "flaggedAt" field to "yelp_reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130805_121458_alter_yelp_reviews_add_flagged_at_column extends CDbMigration
{
    public $tableName = 'yelp_reviews';
    public $columnName = 'flaggedAt';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `flagReason`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}