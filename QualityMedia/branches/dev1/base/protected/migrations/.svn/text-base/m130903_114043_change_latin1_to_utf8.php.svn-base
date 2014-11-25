<?php

class m130903_114043_change_latin1_to_utf8 extends CDbMigration
{
	protected $tables = array('notes', 'yelp_info', 'yelp_analytics', 'yelp_photos');

	public function up()
	{
		foreach ($this->tables as $table) {
			$this->execute('ALTER TABLE ' . $table . ' CONVERT TO CHARACTER SET utf8');
		}
	}

	public function down()
	{
		foreach ($this->tables as $table) {
			$this->execute('ALTER TABLE ' . $table . ' CONVERT TO CHARACTER SET latin1');
		}
	}
}