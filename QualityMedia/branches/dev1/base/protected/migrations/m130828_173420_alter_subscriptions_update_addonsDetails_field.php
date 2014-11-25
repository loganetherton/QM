<?php
/**
 * Alter "subscriptions", update "addonsDetails" field.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130828_173420_alter_subscriptions_update_addonsDetails_field extends CDbMigration
{
    public $tableName = 'subscriptions';
    public $columnName = 'addonsDetails';

	public function up()
	{
        $query = "ALTER TABLE {$this->tableName} MODIFY COLUMN {$this->columnName} TEXT;";

        $this->dbConnection->createCommand($query)->execute();
	}

	public function down()
	{
        $query = "ALTER TABLE {$this->tableName} MODIFY COLUMN {$this->columnName} VARCHAR(255);";
		$this->dbConnection->createCommand($query)->execute();
	}
}