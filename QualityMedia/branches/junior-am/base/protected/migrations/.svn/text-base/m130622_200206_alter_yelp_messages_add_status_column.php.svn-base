<?php
/**
 * Add "status" column to "yelp_messages" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130622_200206_alter_yelp_messages_add_status_column extends CDbMigration
{
    public $tableName = 'yelp_messages';

    public $columnName = 'status';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL AFTER `source`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}