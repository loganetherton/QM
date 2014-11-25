<?php
/**
 * Create "messages" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130720_214621_create_messages_table extends CDbMigration
{
    public $tableName = 'messages';

    public $reviewFK = 'messages_reviews_new_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'                    => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'reviewId'              => 'int(11) UNSIGNED NOT NULL',
            'userId'                => 'varchar(255) NOT NULL COLLATE utf8_general_ci COMMENT "Used in message hash"',
            'messageThread'         => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'messageType'           => 'varchar(100) NOT NULL COLLATE utf8_general_ci',
            'from'                  => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'messageContent'        => 'text(5000) NOT NULL COLLATE utf8_general_ci',
            'messageHash'           => 'char(40) NOT NULL COLLATE utf8_general_ci',
            'messageDate'           => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'processed'             => 'tinyint(1) UNSIGNED NOT NULL',
            'source'                => 'tinyint(1) UNSIGNED NOT NULL',
            'sent'                  => 'tinyint(1) UNSIGNED NOT NULL DEFAULT 1',
            'createdAt'             => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'             => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->reviewFK, $this->tableName, 'reviewId', 'reviews', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->reviewFK, $this->tableName);

        $this->dropTable($this->tableName);
    }
}