<?php
/**
 * Create the email reports table for saved email reports
 *
 * @author Logan Etherton <Logan@qualitymedia.com>
 */
class m140107_070109_create_email_reports_table extends CDbMigration
{
    public $tableName = 'email_reports';
    
    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'                => 'smallint(10) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'accountManagerId'  => 'int(10) NOT NULL',
            'dateRange'         => 'int(25) NOT NULL COMMENT \'Format: StartDateEndDate: YYYYMMDDYYYYMMDD\'',
            'textOrGraph'       => 'varchar(5) NOT NULL',
            'emailContentText'  => 'mediumtext NOT NULL',
            'emailContentGraph' => 'mediumtext NOT NULL',
            'toAddress'         => 'varchar(255) NOT NULL',
            'fromAddress'       => 'varchar(255) NOT NULL',
            'subject'           => 'varchar(255) NOT NULL',
            'yelpBusinessId'    => 'int(32) NOT NULL',
            'createdAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'         => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}