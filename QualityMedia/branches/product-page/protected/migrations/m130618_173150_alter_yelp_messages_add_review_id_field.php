<?php
/**
 * Add "reviewId" column to "yelp_messages" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130618_173150_alter_yelp_messages_add_review_id_field extends CDbMigration
{
    public $tableName = 'yelp_messages';

    public $columnName = 'reviewId';

    public $reviewFk = 'messages_reviews_fk';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'int(11) UNSIGNED NULL AFTER `threadId`');
        $this->addForeignKey($this->reviewFk, $this->tableName, $this->columnName, 'yelp_reviews', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->reviewFk, $this->tableName);
        $this->dropColumn($this->tableName, $this->columnName);
    }
}