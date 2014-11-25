<?php
/**
 * Add "source" field to "yelp_messages" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130620_172417_alter_yelp_message_source_field extends CDbMigration
{
    public $tableName = 'yelp_messages';

    public $columnName = 'source';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL AFTER `messageHash`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}