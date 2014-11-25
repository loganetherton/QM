<?php
/**
 * Add "latestMessageDate" column to "reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130818_222257_alter_reviews_add_latest_message_date_field extends CDbMigration
{
    public $tableName = 'reviews';

    public $columnName = 'latestMessageDate';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `messagesFolder`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}