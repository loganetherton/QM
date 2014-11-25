<?php
/**
 * Add "flagged" field to "reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130724_125627_alter_reviews_add_flagged_field extends CDbMigration
{
    public $tableName = 'yelp_reviews';

    public $columnName = 'flagged';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL AFTER `status`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}