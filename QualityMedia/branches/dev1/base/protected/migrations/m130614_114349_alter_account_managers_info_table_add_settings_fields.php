<?php
/**
 * Change "account_managers" table by adding settings fields
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130614_114349_alter_account_managers_info_table_add_settings_fields extends CDbMigration
{
    public $tableName = 'account_managers';

    public $columnNames = array(
        'email'     => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci AFTER `username`',
        'firstName' => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci AFTER `email`',
        'lastName'  => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci AFTER `firstName`',
        'showOnlyLinkedFeeds'=> 'tinyint(1) UNSIGNED NOT NULL AFTER `salt`',
        'state'=> 'tinyint(1) UNSIGNED NOT NULL AFTER `showOnlyLinkedFeeds`',
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