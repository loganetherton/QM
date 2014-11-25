<?php
/**
 * Add "lastActionAt" column to "yelp_reviews" table.
 *
 * @author Nitesh Pandey <nitesh@nitesh.com.np>
 */
class m130726_104306_alter_yelp_reviews_add_lastActionAt_column extends CDbMigration
{
    public $tableName = 'yelp_reviews';
    public $columnName = 'lastActionAt';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `sent`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }

}