<?php

class m130723_215831_create_yelp_analytics extends CDbMigration
{
    protected $table = 'yelp_analytics';
    protected $fk = 'yelp_analytic_users_fk';

	public function up()
	{
        $this->createTable($this->table, array(
            'id' => 'INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT',
            'businessId' => 'INT(11) UNSIGNED NOT NULL DEFAULT 0',
            'info' => 'text NOT NULL',
            'createdAt' => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt' => 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ));

        $this->addForeignKey($this->fk, $this->table, 'businessId', 'users', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		$this->dropForeignKey($this->fk, $this->table);
        $this->dropTable($this->table);
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