<?php
/**
 * Change fields in "yelp_message_threads" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130618_000815_alter_yelp_message_threads_rename_fields extends CDbMigration
{
    public $tableName = 'yelp_message_threads';

    public $columns = array(
        'businessId'=>array(
            'new'   => 'int(11) UNSIGNED NOT NULL',
            'old'   => 'varchar(50) NOT NULL COLLATE utf8_general_ci',
        ),
        'userId'=>array(
            'new'   => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'old'   => 'varchar(50) NOT NULL COLLATE utf8_general_ci',
        ),
        'userName'=>array(
            'new'   => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'old'   => 'varchar(50) NOT NULL COLLATE utf8_general_ci',
        ),
        'subject'=>array(
            'new'   => 'varchar(255) NOT NULL COLLATE utf8_general_ci',
            'old'   => 'text NULL COLLATE utf8_general_ci',
        ),
    );

    public function up()
    {
        foreach($this->columns as $column => $type) {
            $this->alterColumn($this->tableName, $column, $type['new']);
        }
    }

    public function down()
    {
        foreach($this->columns as $column => $type) {
            $this->alterColumn($this->tableName, $column, $type['old']);
        }
    }
}