<?php
/**
 * Extend "label" field length in "yelp_business" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m131220_202819_alter_yelp_businesses_extends_label_field_length extends CDbMigration
{
    public $tableName = 'yelp_businesses';

    public $columnName = 'label';

    public function up()
    {
        $this->alterColumn($this->tableName, $this->columnName, 'VARCHAR(255) NOT NULL COLLATE utf8_general_ci');
    }

    public function down()
    {
        $this->alterColumn($this->tableName, $this->columnName, 'VARCHAR(100) NOT NULL COLLATE utf8_general_ci');
    }
}