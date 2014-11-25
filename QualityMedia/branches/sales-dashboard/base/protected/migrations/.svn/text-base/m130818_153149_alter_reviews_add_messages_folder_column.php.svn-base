<?php
/**
 * Add "messagesFolder" column to "reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130818_153149_alter_reviews_add_messages_folder_column extends CDbMigration
{
    public $tableName = 'reviews';

    public $columnName = 'messagesFolder';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TINYINT(1) UNSIGNED NOT NULL AFTER `status`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}