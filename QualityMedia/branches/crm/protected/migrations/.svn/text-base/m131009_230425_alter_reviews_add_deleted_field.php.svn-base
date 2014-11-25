<?php
/**
 * Add "deleted" field into "reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m131009_230425_alter_reviews_add_deleted_field extends CDbMigration
{
    public $tableName = 'reviews';

    public $columnName = 'deleted';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `status`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}