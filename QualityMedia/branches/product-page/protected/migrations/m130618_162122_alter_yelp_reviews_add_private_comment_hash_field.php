<?php
/**
 * Add "privateConversationThread" into "yelp_reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130618_162122_alter_yelp_reviews_add_private_comment_hash_field extends CDbMigration
{
    public $tableName = 'yelp_reviews';

    public $columnName = 'privateConversationThread';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'varchar(50) NOT NULL COLLATE utf8_general_ci AFTER `publicCommentDate`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}