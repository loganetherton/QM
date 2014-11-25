<?php

class m130716_152000_alter_notes_fk extends CDbMigration
{
    protected $table = 'notes';
    protected $usersFk = 'notes_users_fk';
    protected $amFk = 'notes_am_fk';

	public function up()
	{
        $this->alterColumn($this->table, 'userId', 'INT(11) UNSIGNED NOT NULL');
        $this->alterColumn($this->table, 'accountManagerId', 'INT(11) UNSIGNED NOT NULL');
        $this->addForeignKey($this->usersFk, $this->table, 'userId', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey($this->amFk, $this->table, 'accountManagerId', 'account_managers', 'id', 'CASCADE', 'CASCADE');
        $this->alterColumn($this->table, 'createdAt', 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->alterColumn($this->table, 'updatedAt', 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"');
	}

	public function down()
	{
		$this->dropForeignKey($this->usersFk, $this->table);
        $this->dropForeignKey($this->amFk, $this->table);

        $this->alterColumn($this->table, 'createdAt', 'datetime NOT NULL');
        $this->alterColumn($this->table, 'updatedAt', 'datetime NOT NULL');
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