<?php
/**
 * Create "Yelp reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130513_100138_create_yelp_reviews_table extends CDbMigration
{
    public $tableName = 'yelp_reviews';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'                => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'businessId'        => 'int(11) UNSIGNED NOT NULL',
            'reviewId'          => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'reviewHash'        => 'char(40) NOT NULL COLLATE utf8_general_ci',
            'starRating'        => 'tinyint(1) NOT NULL',
            'reviewDate'        => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'content'           => 'text(5000) NOT NULL COLLATE utf8_general_ci',
            'userId'            => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'userPhotoLink'     => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'userFriendCount'   => 'smallint(5) UNSIGNED NOT NULL',
            'userReviewCount'   => 'smallint(5) UNSIGNED NOT NULL',
            'userName'          => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'userYelpProfile'   => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'userLocation'      => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'createdAt'         => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'         => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}