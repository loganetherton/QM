<?php

class m130826_155425_notes_subject extends CDbMigration
{
	public function up()
	{
		$this->addColumn('notes', 'subject', 'varchar(255) not null default "" AFTER userId');
	}

	public function down()
	{
		$this->dropColumn('notes', 'subject');
	}
}