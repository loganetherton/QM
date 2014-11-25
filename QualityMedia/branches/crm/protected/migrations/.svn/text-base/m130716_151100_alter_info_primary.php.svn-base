<?php

class m130716_151100_alter_info_primary extends CDbMigration
{
    protected $table = 'yelp_info';
    protected $usersFk = 'yelp_info_users_fk';

    public function up()
    {
        $this->truncateTable($this->table);

        $this->dropPrimaryKey('PRIMARY', $this->table);

        $this->alterColumn($this->table, 'businessId', 'INT(11) UNSIGNED NOT NULL');
        $this->alterColumn($this->table, 'createdAt', 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"');
        $this->alterColumn($this->table, 'updatedAt', 'timestamp NOT NULL DEFAULT "0000-00-00 00:00:00"');

        $this->addColumn($this->table, 'id', 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
        $this->addColumn($this->table, 'originalNodes', 'varchar(255) NOT NULL DEFAULT "" AFTER info');

        $this->addForeignKey($this->usersFk, $this->table, 'businessId', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropColumn($this->table, 'id');
        $this->addPrimaryKey('businessId', $this->table, 'businessId');
        $this->dropForeignKey($this->usersFk, $this->table);

        $this->alterColumn($this->table, 'createdAt', 'datetime NOT NULL');
        $this->alterColumn($this->table, 'updatedAt', 'datetime NOT NULL');
    }

    /**
     * This is a fix for Yii core code.
     * Builds and executes a SQL statement for removing a primary key, supports composite primary keys.
     * @param string $name name of the constraint to remove
     * @param string $table name of the table to remove primary key from
     * @since 1.1.13
     */
    public function dropPrimaryKey($name,$table)
    {
        echo "    > alter table $table drop constraint $name primary key ...";
        $time=microtime(true);
        $this->getDbConnection()->createCommand()->dropPrimaryKey($name,$table);
        echo " done (time: ".sprintf('%.3f', microtime(true)-$time)."s)\n";
    }
}