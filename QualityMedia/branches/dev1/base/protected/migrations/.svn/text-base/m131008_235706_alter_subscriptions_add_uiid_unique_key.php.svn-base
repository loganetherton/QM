<?php
/**
 * Adds unique index to uuid column
 *
 * @author
 */
class m131008_235706_alter_subscriptions_add_uiid_unique_key extends CDbMigration
{
    protected $table = 'subscriptions';

    protected $column = 'uuid';

    public function up()
    {
        $query = "DELETE FROM {$this->table}
        WHERE id NOT IN (SELECT *
            FROM (SELECT MAX(n.id)
                FROM {$this->table} n
                GROUP BY {$this->column}
            ) x
        )";

        $this->dbConnection->createCommand($query)->execute();

        $this->createIndex($this->column, $this->table, $this->column, true);
    }

    public function down()
    {
        $this->dropIndex($this->column, $this->table);
    }
}