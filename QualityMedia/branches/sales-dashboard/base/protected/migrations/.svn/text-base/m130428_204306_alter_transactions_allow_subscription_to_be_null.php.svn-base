<?php
/**
 * Alter "transactions" table.
 * Allow subscriptionId to be null (to allow transaction outside of subscription).
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130428_204306_alter_transactions_allow_subscription_to_be_null extends CDbMigration
{
    public $tableName = 'transactions';

    public $columnName = 'subscriptionId';

    public function up()
    {
        $this->alterColumn($this->tableName, $this->columnName, 'INT(11) UNSIGNED NULL');
    }

    public function down()
    {
        $this->alterColumn($this->tableName, $this->columnName, 'INT(11) UNSIGNED NOT NULL');
    }
}