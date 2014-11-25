<?php

class m130716_151513_alter_yelp_photos_foreign extends CDbMigration
{
    protected $table = 'yelp_photos';
    protected $userFk = 'photos_users_fk';

	public function up()
	{
        $this->alterColumn($this->table, 'businessId', 'INT(11) UNSIGNED NOT NULL');
        $this->addForeignKey($this->userFk, $this->table, 'businessId', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->alterColumn($this->table, 'createdAt', 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->alterColumn($this->table, 'updatedAt', 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"');
	}

	public function down()
	{
		$this->dropForeignKey($this->usersFk, $this->table);

        $this->alterColumn($this->table, 'createdAt', 'datetime NOT NULL');
        $this->alterColumn($this->table, 'updatedAt', 'datetime NOT NULL');
	}
}