<?php

class m130711_084945_create_notes_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('notes', array(
            'id' => 'int NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'accountManagerId' => 'int NOT NULL',
            'userId' => 'int NOT NULL',
            'note' => 'text NOT NULL',
            'createdAt' => 'datetime NOT NULL',
            'updatedAt' => 'datetime NOT NULL',
            'dueAt' => 'datetime NOT NULL',
            'important' => 'tinyint(1) NOT NULL DEFAULT 0',
            'archived' => 'tinyint(1) NOT NULL DEFAULT 0',
        ));
	}

	public function down()
	{
		$this->dropTable('notes');
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