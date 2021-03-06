<?php
/**
 * Add "yelpBusinessId" field to "yelp_analytics" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130919_213110_alter_yelp_analytics_add_yelp_business_id_field extends CDbMigration
{
    public $tableName = 'yelp_analytics';

    public $columnName = 'yelpBusinessId';
    public $yelpBusinessFK  = 'yelp_analytics_yelp_businesses_fk';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'INT(11) UNSIGNED NOT NULL AFTER `businessId`');

        // Update column with related values.
        // [!!] This needs to be done before adding a foreign key constraint
        $this->execute("UPDATE {$this->tableName} r
                        LEFT JOIN yelp_businesses yb ON r.businessId = yb.userId
                        SET r.{$this->columnName} = yb.id");

        $this->addForeignKey($this->yelpBusinessFK, $this->tableName, $this->columnName, 'yelp_businesses', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->yelpBusinessFK, $this->tableName);

        $this->dropColumn($this->tableName, $this->columnName);
    }
}