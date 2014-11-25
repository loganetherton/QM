<?php
/**
 * Add "status" column to "yelp_message_threads" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130619_171603_alter_message_threads_add_status_field extends CDbMigration
{
    public $tableName = 'yelp_message_threads';

    public $columnName = 'status';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL AFTER `excerpt`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}