<?php
/**
 * Alter the date range column to varchar
 *
 * @author Logan Etherton <logan@qualitymedia.com
 */
class m140113_094331_alter_email_reports_table extends CDbMigration
{
    public $tableName = 'email_reports';
    
    public function up()
    {
        $this->alterColumn($this->tableName, 'dateRange', 'varchar(16) not null');
    }
}