<?php
/**
 * Add Junior Am relatede fields into "yelp_reviews" table.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m130928_212631_alter_account_managers_add_jr_am_related_fields extends CDbMigration
{
    public $tableName = 'account_managers';

    public function up()
    {
        $this->addColumn($this->tableName, 'type', 'tinyint(1) UNSIGNED NOT NULL AFTER `showOnlyLinkedFeeds`');
        $this->addColumn($this->tableName, 'seniorManagerId', 'INT(11) UNSIGNED NOT NULL AFTER `type`');
    }

    public function down()
    {
        $this->dropColumn($this->tableName, 'type');
        $this->dropColumn($this->tableName, 'seniorManagerId');
    }
}