<?php
/**
 * Add "startedAt" field to "subscriptions" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130702_111054_alter_subscriptions_add_started_at_field extends CDbMigration
{
    public $tableName = 'subscriptions';

    public $columnName = 'startedAt';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `trialEndsAt`');
        $this->execute("UPDATE ".$this->tableName." SET startedAt = createdAt WHERE startedAt = '0000-00-00 00:00:00'");
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}