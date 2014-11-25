<?php

class m130905_175840_review_notes extends CDbMigration
{
	public function up()
	{
		$this->addColumn('notes', 'reviewId', 'int(11) UNSIGNED NULL AFTER userId');
		$this->addForeignKey('notes_review_fk', 'notes', 'reviewId', 'reviews', 'id', 'CASCADE', 'CASCADE');
		$this->addColumn('notes', 'type', 'varchar(10) NOT NULL DEFAULT "client" AFTER reviewId');
	}

	public function down()
	{
		$this->dropForeignKey('notes_review_fk', 'notes');
		$this->dropColumn('notes', 'reviewId');
		$this->dropColumn('notes', 'type');
	}
}