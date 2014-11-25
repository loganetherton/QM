<?php
/**
 * Add "review_updates" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130721_070018_create_review_updates_table extends CDbMigration
{
    public $tableName = 'review_updates';

    public $reviewFK = 'review_updates_reviews_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'reviewId'      => 'int(11) UNSIGNED NOT NULL',
            'updateId'      => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'updateContent' => 'text(5000) NOT NULL COLLATE utf8_general_ci',
            'updateHash'    => 'char(40) NOT NULL COLLATE utf8_general_ci',
            'starRating'    => 'tinyint(1) NOT NULL',
            'updateDate'    => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'createdAt'     => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->reviewFK, $this->tableName, 'reviewId', 'reviews', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->reviewFK, $this->tableName);

        $this->dropTable($this->tableName);
    }
}