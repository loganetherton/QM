<?php
/**
 * Add "messageDate" field to "yelp_messages" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130624_160519_alter_yelp_messages_add_message_date_field extends CDbMigration
{
    public $tableName = 'yelp_messages';

    public $columnName = 'messageDate';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `messageHash`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}