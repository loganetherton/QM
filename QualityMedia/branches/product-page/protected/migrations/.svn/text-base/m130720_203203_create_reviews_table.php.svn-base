<?php
/**
 * Create "reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130720_203203_create_reviews_table extends CDbMigration
{
    public $tableName = 'reviews';

    public $businessFK = 'reviews_users_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'                    => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'businessId'            => 'int(11) UNSIGNED NOT NULL',
            'reviewId'              => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'reviewContent'         => 'text(5000) NOT NULL COLLATE utf8_general_ci',
            'starRating'            => 'tinyint(1) NOT NULL',
            'reviewDate'            => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'userId'                => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'userName'              => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'userLocation'          => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'userPhotoLink'         => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'userFriendCount'       => 'smallint(5) UNSIGNED NOT NULL',
            'userReviewCount'       => 'smallint(5) UNSIGNED NOT NULL',
            'publicCommentContent'  => 'text(5000) NOT NULL COLLATE utf8_general_ci',
            'publicCommentAuthor'   => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'publicCommentDate'     => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'processed'             => 'tinyint(1) UNSIGNED NOT NULL',
            'status'                => 'tinyint(1) UNSIGNED NOT NULL',
            'createdAt'             => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'             => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->businessFK, $this->tableName, 'businessId', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->businessFK, $this->tableName);

        $this->dropTable($this->tableName);
    }
}