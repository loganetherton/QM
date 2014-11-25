<?php
/**
 * Add "sent" column to "yelp_messages" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130625_144941_alter_yelp_messages_add_sent_field extends CDbMigration
{
    public $tableName = 'yelp_messages';

    public $columnName = 'sent';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL AFTER `status`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}