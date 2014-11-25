<?php
/**
 * Add new columns into "yelp_reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130617_082520_alter_yelp_reviews_add_answer_fields extends CDbMigration
{
    public $tableName = 'yelp_reviews';

    public $columns = array(
        'publicComment'         => 'text(5000) NOT NULL COLLATE utf8_general_ci AFTER `userLocation`',
        'publicCommentAuthor'   => 'varchar(255) NOT NULL COLLATE utf8_general_ci AFTER `publicComment`',
        'publicCommentDate'     => 'timestamp DEFAULT "0000-00-00 00:00:00" AFTER `publicCommentAuthor`',
    );

    public function up()
    {
        foreach($this->columns as $column => $type) {
            $this->addColumn($this->tableName, $column, $type);
        }
    }

    public function down()
    {
        foreach($this->columns as $column => $type) {
            $this->dropColumn($this->tableName, $column);
        }
    }
}