<?php
/**
 * Create "yelp_info" table.
 */
class m130714_200550_create_info_table extends CDbMigration
{
    public $tableName = 'yelp_info';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'businessId'    => 'int(11) UNSIGNED NOT NULL PRIMARY KEY',
            'info'          => 'text NOT NULL',
            'saved'         => 'tinyint(1) NOT NULL DEFAULT 0',
            'createdAt'     => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00"',
        ));
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}