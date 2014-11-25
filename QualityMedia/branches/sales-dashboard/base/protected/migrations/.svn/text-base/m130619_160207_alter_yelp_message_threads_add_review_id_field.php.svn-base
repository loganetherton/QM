<?php
/**
 * Add "reviewId" column to "yelp_message_threads" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130619_160207_alter_yelp_message_threads_add_review_id_field extends CDbMigration
{
    public $tableName = 'yelp_message_threads';

    public $columnName = 'reviewId';

    public $reviewFk = 'message_threads_reviews_fk';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'int(11) UNSIGNED NULL AFTER `businessId`');

        $this->addForeignKey($this->reviewFk, $this->tableName, $this->columnName, 'yelp_reviews', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->reviewFk, $this->tableName);

        $this->dropColumn($this->tableName, $this->columnName);
    }
}