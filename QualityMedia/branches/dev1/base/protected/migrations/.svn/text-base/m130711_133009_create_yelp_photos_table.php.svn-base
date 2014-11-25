<?php

class m130711_133009_create_yelp_photos_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('yelp_photos', array(
            'id' => 'int NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'businessId' => 'int NOT NULL',
            'photoId' => 'varchar(100) NOT NULL',
            'uploaderName' => 'varchar(100) NOT NULL DEFAULT ""',
            'uploaderId' => 'varchar(100) NOT NULL DEFAULT ""',
            'uploaderProfile' => 'varchar(255) NOT NULL DEFAULT ""',
            'caption' => 'varchar(255) NOT NULL',
            'photoUrl' => 'varchar(255) NOT NULL',
            'createdAt' => 'datetime NOT NULL',
            'updatedAt' => 'datetime NOT NULL',
            'actions' => 'varchar(50) NOT NULL DEFAULT ""',
            'fromOwner' => 'tinyint(1) NOT NULL DEFAULT 0',
            'flagged' => 'tinyint(1) NOT NULL DEFAULT 0',
            'uploaded' => 'tinyint(1) NOT NULL DEFAULT 0',
            'deleted' => 'tinyint(1) NOT NULL DEFAULT 0',
            'saved' => 'tinyint(1) NOT NULL DEFAULT 0',
        ));
	}

	public function down()
	{
		$this->dropTable('yelp_photos');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}