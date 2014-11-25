<?php
/**
 * Add "status" field into "yelp_reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130619_080712_alter_yelp_reviews_add_status_field extends CDbMigration
{
    public $tableName = 'yelp_reviews';

    public $columnName = 'status';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL AFTER `privateConversationThread`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}