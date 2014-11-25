<?php
/**
 * Add "flagReason" field to "reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130729_190623_alter_reviews_add_flag_reason_field extends CDbMigration
{
    public $tableName = 'yelp_reviews';

    public $columnName = 'flagReason';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'text AFTER `flagged`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}