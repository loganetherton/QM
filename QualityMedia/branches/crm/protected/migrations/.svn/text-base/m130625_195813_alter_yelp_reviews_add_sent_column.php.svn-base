<?php
/**
 * Add "sent" to "yelp_reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130625_195813_alter_yelp_reviews_add_sent_column extends CDbMigration
{
    public $tableName = 'yelp_reviews';

    public $columnName = 'sent';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL DEFAULT 1 AFTER `status`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}