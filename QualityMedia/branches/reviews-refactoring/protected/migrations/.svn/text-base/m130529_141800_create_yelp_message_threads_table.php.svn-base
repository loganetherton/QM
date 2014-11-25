<?php

class m130529_141800_create_yelp_message_threads_table extends CDbMigration
{
    public $tableName = 'yelp_message_threads';

	public function up()
	{
        $this->createTable($this->tableName, array(
            'id'                => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'businessId'              => 'varchar(50) NOT NULL COLLATE utf8_general_ci',
            'threadYelpId'              => 'varchar(50) NOT NULL COLLATE utf8_general_ci',
            'userYelpId'              => 'varchar(50) NOT NULL COLLATE utf8_general_ci',
            'userName'           => 'varchar(50) NOT NULL COLLATE utf8_general_ci',
            'userImageUrl'           => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'subject'            => 'text(5000) COLLATE utf8_general_ci',
            'excerpt'              => 'varchar(100) NOT NULL COLLATE utf8_general_ci',
            'createdAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
	}

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}