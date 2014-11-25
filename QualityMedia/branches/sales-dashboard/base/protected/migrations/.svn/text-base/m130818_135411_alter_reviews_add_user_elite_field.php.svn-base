<?php
/**
 * Add "userElite" column to "reviews" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130818_135411_alter_reviews_add_user_elite_field extends CDbMigration
{
    public $tableName = 'reviews';

    public $columnName = 'userElite';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'TINYINT(1) UNSIGNED NOT NULL AFTER `userName`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}