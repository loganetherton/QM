<?php
/**
 * Update recurly validation result fields length.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130501_075756_alter_transactions_update_recurly_validation_results_fields_length extends CDbMigration
{
    public $tableName = 'transactions';

    public $columnNames = array('cvvResult', 'avsResult', 'avsResultStreet', 'avsResultPostal');

    public function up()
    {
        foreach($this->columnNames as $column) {
            $this->alterColumn($this->tableName, $column, 'VARCHAR(100) NOT NULL COLLATE utf8_general_ci');
        }
    }

    public function down()
    {
        foreach($this->columnNames as $column) {
            $this->alterColumn($this->tableName, $column, 'VARCHAR(50) NOT NULL COLLATE utf8_general_ci');
        }
    }
}