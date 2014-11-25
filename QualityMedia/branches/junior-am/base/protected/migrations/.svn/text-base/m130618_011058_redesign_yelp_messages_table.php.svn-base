<?php
/**
 * Redesign "yelp_messages" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130618_011058_redesign_yelp_messages_table extends CDbMigration
{
    public $tableName = 'yelp_messages';

    public $threadFk = 'messages_threads_fk';

    public function up()
    {
        $this->dropColumn($this->tableName, 'heading');
        $this->dropColumn($this->tableName, 'starRating');
        $this->dropColumn($this->tableName, 'time');

        $this->renameColumn($this->tableName, 'content', 'message');

        $this->alterColumn($this->tableName, 'message', 'TEXT(5000) NOT NULL COLLATE utf8_general_ci');
        $this->alterColumn($this->tableName, 'from', 'varchar(255) NOT NULL COLLATE utf8_general_ci');

        $this->addColumn($this->tableName, 'messageHash', 'char(40) NOT NULL COLLATE utf8_general_ci AFTER `message`');

        $this->addColumn($this->tableName, 'threadId', 'int(11) UNSIGNED NOT NULL AFTER `id`');
        $this->addForeignKey($this->threadFk, $this->tableName, 'threadId', 'yelp_message_threads', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->threadFk, $this->tableName);
        $this->dropColumn($this->tableName, 'threadId');

        $this->dropColumn($this->tableName, 'messageHash');

        $this->alterColumn($this->tableName, 'from', 'varchar(100) NOT NULL COLLATE utf8_general_ci');
        $this->alterColumn($this->tableName, 'message', 'TEXT NULL COLLATE utf8_general_ci');

        $this->renameColumn($this->tableName, 'message', 'content');

        $this->addColumn($this->tableName, 'heading', 'varchar(255) NOT NULL COLLATE utf8_general_ci AFTER `content`');
        $this->addColumn($this->tableName, 'starRating', 'smallint(6) NOT NULL AFTER `heading`');
        $this->addColumn($this->tableName, 'time', 'varchar(20) NOT NULL COLLATE utf8_general_ci AFTER `starRating`');
    }
}