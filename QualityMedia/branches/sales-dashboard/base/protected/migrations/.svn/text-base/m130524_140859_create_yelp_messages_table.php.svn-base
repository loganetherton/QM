<?php
/**
 * Create "yelp_messages" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130524_140859_create_yelp_messages_table extends CDbMigration
{
    public $tableName = 'yelp_messages';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'                => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'type'              => 'varchar(100) NOT NULL COLLATE utf8_general_ci',
            'from'              => 'varchar(100) NOT NULL COLLATE utf8_general_ci',
            'content'           => 'text(5000) COLLATE utf8_general_ci',
            'heading'           => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'starRating'        => 'smallint(6) NOT NULL',
            'time'              => 'varchar(20) NOT NULL COLLATE utf8_general_ci',
            'createdAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}