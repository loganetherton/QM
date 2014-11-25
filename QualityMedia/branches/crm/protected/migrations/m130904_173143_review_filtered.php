<?php

class m130904_173143_review_filtered extends CDbMigration
{
	public function up()
	{
		$this->addColumn('reviews', 'filtered', 'tinyint(1) unsigned NOT NULL DEFAULT 0 AFTER userElite');
	}

	public function down()
	{
		$this->dropColumn('reviews', 'filtered');
	}
}