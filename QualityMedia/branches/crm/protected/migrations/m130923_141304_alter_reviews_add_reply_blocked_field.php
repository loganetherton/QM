<?php
/**
 * Alter "reviews", add "replyBlocked" field.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130923_141304_alter_reviews_add_reply_blocked_field extends CDbMigration
{
    public $tableName = 'reviews';

    public $columnName = 'replyBlocked';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'tinyint(1) UNSIGNED NOT NULL AFTER `messagesFolder`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}