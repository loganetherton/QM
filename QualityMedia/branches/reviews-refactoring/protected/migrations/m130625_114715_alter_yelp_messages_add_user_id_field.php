<?php
/**
 * Add "userId" column to "yelp_messages" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130625_114715_alter_yelp_messages_add_user_id_field extends CDbMigration
{
    public $tableName = 'yelp_messages';

    public $columnName = 'userId';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'varchar(255) NOT NULL COLLATE utf8_general_ci AFTER `from`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }
}