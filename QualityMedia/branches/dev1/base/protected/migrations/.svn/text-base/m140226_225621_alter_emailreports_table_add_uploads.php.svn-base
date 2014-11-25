<?php
/**
 * Change "email_reports" table by adding image file column
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 */
class m140226_225621_alter_emailreports_table_add_uploads extends CDbMigration
{
    public $tableName = 'email_reports';

    public $columnNames = array(
        'image_attachment' => 'VARCHAR(200) NOT NULL AFTER `yelpBusinessId`',
    );

    public function up()
    {
        foreach($this->columnNames as $column => $options) {
            $this->addColumn($this->tableName, $column, $options);
        }
    }

    public function down()
    {
        foreach(array_keys($this->columnNames) as $column) {
            $this->dropColumn($this->tableName, $column);
        }
    }
}