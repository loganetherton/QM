<?php
/**
 * Add "lastActionAt" column to "reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130818_103603_alter_reviews_add_last_action_at_field extends CDbMigration
{
    public $tableName = 'reviews';

    public $columnName = 'lastActionAt';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TIMESTAMP NOT NULL DEFAULT "0000-00-00 00:00:00" AFTER `status`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}