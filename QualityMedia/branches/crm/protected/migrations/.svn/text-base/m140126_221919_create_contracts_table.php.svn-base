<?php
/**
 * Create the contracts table for contract entry
 *
 * @author Logan Etherton <Logan@qualitymedia.com>
 */
class m140126_221919_create_contracts_table extends CDbMigration
{
    public $tableName = 'contracts';
    
    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'                        => 'smallint(10) NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'createdAt'                 => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'                 => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'approved'                  => 'tinyint(1) NOT NULL',
            'salesmanId'                => 'smallint(10)',
            'accountManagerId'          => 'smallint(10)',
            'csId'                      => 'smallint(10)',
            'adminId'                   => 'smallint(10)',
            'accountStatus'             => 'smallint(3) NOT NULL',
            'dealType'                  => 'smallint(3) NOT NULL',
            'setupFee'                  => 'int(255) NOT NULL',
            'businessType'              => 'varchar(255) NOT NULL',
            'notesSales'                => 'mediumtext',
            'notesAm'                   => 'mediumtext',
            'notesCs'                   => 'mediumtext',
            'notesAdmin'                => 'mediumtext',
            'contractDate'              => 'date NOT NULL',
            'trialMaturityDate'         => 'date',
            'cancellationDate'          => 'date',
            'bestTimeToReach'           => 'varchar(255) NOT NULL',
            'welcomeCallTime'           => 'datetime',
            'welcomeCallStatus'         => 'tinyint(1) NOT NULL',
            'paymentType'               => 'tinyint(1) NOT NULL',
            'moneyBackGuarantee'        => 'tinyint(1) NOT NULL',
            'companyName'               => 'varchar(511) NOT NULL',
            'contactName'               => 'varchar(511) NOT NULL',
            'contactTitle'              => 'varchar(511) NOT NULL',
            'cardholderName'            => 'varchar(511)',
            'creditCardType'            => 'int(7)',
            'ccNumber'                  => 'varchar(256)',
            'ccLast4Digits'             => 'varchar(4)',
            'ccExpiration'              => 'varchar(15)',
            'cvv'                       => 'varchar(256)',
            'email'                     => 'varchar(511) NOT NULL',
            'phone'                     => 'varchar(127) NOT NULL',
            'phoneSecondary'            => 'varchar(127)',
            'address1'                  => 'varchar(511) NOT NULL',
            'address2'                  => 'varchar(511)',
            'city'                      => 'varchar(255) NOT NULL',
            'state'                     => 'varchar(255) NOT NULL',
            'zip'                       => 'varchar(15) NOT NULL',
            'country'                   => 'varchar(255) NOT NULL',
            'timeZone'                  => 'varchar(63) NOT NULL',
            'clientUrl'                 => 'varchar(1023) NOT NULL',
            'contractUrl'               => 'varchar(1023) NOT NULL',
            'billingAddress1'           => 'varchar(511) NOT NULL',
            'billingAddress2'           => 'varchar(511)',
            'billingCity'               => 'varchar(255) NOT NULL',
            'billingState'              => 'varchar(255) NOT NULL',
            'billingZip'                => 'varchar(15) NOT NULL',
            'billingCountry'            => 'varchar(255) NOT NULL',
            'trialServiceYelp'          => 'tinyint(1)',
            'trialServiceTwitter'       => 'tinyint(1)',
            'trialServiceGooglePlus'    => 'tinyint(1)',
            'trialServiceFacebook'      => 'tinyint(1)',
            'trialServiceTripAdvisor'   => 'tinyint(1)',
            'trialServiceFoursquare'    => 'tinyint(1)',
            'trialServiceEmail'         => 'tinyint(1)',
        ), 'ENGINE = InnoDB CHARSET = utf8;');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}