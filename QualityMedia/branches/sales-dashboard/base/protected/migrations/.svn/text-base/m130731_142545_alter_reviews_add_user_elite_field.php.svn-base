<?php
/**
 * Add "userElite" field to "reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130731_142545_alter_reviews_add_user_elite_field extends CDbMigration
{
    public $tableName = 'yelp_reviews';

    public $columnName = 'userElite';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL AFTER `userLocation`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}