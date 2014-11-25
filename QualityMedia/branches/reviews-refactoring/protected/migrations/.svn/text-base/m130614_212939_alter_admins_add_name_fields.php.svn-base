<?php
/**
 * Change "admins" table by adding firsName and LastName fields
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130614_212939_alter_admins_add_name_fields extends CDbMigration
{
    public $tableName = 'admins';

    public $columnNames = array(
        'firstName' => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci AFTER `username`',
        'lastName'  => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci AFTER `firstName`',
    );

    public function up()
    {
        foreach($this->columnNames as $column => $options) {
            $this->addColumn($this->tableName, $column, $options);
        }
    }

    public function down()
    {
        foreach(array_keys($this->columnNames) as $column) {
            $this->dropColumn($this->tableName, $column);
        }
    }
}