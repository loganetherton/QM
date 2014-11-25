<?php
/**
 * Creates a table for storing tweets
 *
 * @author Nitesh Pandey <nitesh@qualitymedia.com>
 */
class m130924_162899_create_tweets_table extends CDbMigration
{
    protected $table = 'tweets';
    
    public function up()
    {
        $this->createTable($this->table, array(
            'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'statusId' => 'VARCHAR(32) NOT NULL',
            'rawStatus' => 'text NOT NULL',
            'text' => 'VARCHAR(255) NOT NULL',
            'twitterUserId' => 'VARCHAR(50) NOT NULL',
            'inReplyToStatusId' => 'VARCHAR(50) NOT NULL',
            'retweeted' => 'TINYINT(1) NOT NULL DEFAULT 0',
            'favoriteCount' => 'INT(10) UNSIGNED NOT NULL DEFAULT 0',
            'retweetCount' => 'INT(10) UNSIGNED NOT NULL DEFAULT 0',
            'retweetedStatusId' => 'VARCHAR(32) NOT NULL',
            'user'  => 'TEXT NOT NULL',
            'createdAt' => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt' => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ));
    }

    public function down()
    {
        $this->dropTable($this->table);
    }
}