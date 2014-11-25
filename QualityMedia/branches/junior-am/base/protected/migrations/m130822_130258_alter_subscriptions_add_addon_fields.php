<?php
/**
 * Change "subscriptions" table by adding firsName and LastName fields
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130822_130258_alter_subscriptions_add_addon_fields extends CDbMigration
{
    public $tableName = 'subscriptions';

    public $columnNames = array(
        'addonsDetails'      => 'VARCHAR(255) COLLATE utf8_general_ci AFTER `currency`',
        'addonsTotalAmount'  => 'VARCHAR(255) COLLATE utf8_general_ci AFTER `addonsDetails`',
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