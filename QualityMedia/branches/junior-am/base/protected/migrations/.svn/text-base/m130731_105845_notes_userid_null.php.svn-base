<?php

class m130731_105845_notes_userid_null extends CDbMigration
{
	public function up()
	{
        $this->alterColumn('notes', 'userId', 'INT(11) UNSIGNED NULL');
	}

	public function down()
	{
		$this->alterColumn('notes', 'userId', 'INT(11) UNSIGNED NOT NULL');
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