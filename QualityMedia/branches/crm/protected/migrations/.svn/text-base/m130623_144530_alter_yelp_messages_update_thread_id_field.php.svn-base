<?php
/**
 * Alter "yelp_messages", update "threadId" field.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130623_144530_alter_yelp_messages_update_thread_id_field extends CDbMigration
{
    public $tableName = 'yelp_messages';

    public $columnName = 'threadId';

    public function up()
    {
        $this->alterColumn($this->tableName, $this->columnName, 'int(11) UNSIGNED NULL');
    }

    public function down()
    {
        $this->alterColumn($this->tableName, $this->columnName, 'int(11) UNSIGNED NOT NULL');
    }
}