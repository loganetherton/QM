<?php
/**
 * Create "plans" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130507_230506_create_plans_table extends CDbMigration
{
    public $tableName = 'plans';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'planCode'      => 'varchar(50) NOT  NULL COLLATE utf8_general_ci',
            'name'          => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'amount'        => 'mediumint(8) UNSIGNED NOT NULL',
            'setupFee'      => 'mediumint(8) UNSIGNED NOT NULL',
            'intervalLength'=> 'smallint(3) UNSIGNED NOT NULL',
            'intervalUnit'  => 'varchar(10) NOT NULL COLLATE utf8_general_ci',
            'createdAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}