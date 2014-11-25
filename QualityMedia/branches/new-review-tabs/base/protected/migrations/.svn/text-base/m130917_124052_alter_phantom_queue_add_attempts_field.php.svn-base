<?php
/**
 * Add "attempts" column to "phantom_queue" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130917_124052_alter_phantom_queue_add_attempts_field extends CDbMigration
{
    public $tableName = 'phantom_queue';

    public $columnName = 'attempts';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'INT NOT NULL DEFAULT 0 AFTER `errorReason`');

        $sql = 'UPDATE `'.$this->tableName.'` SET attempts = 1 WHERE `status` != 0';
        $this->execute($sql);
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}