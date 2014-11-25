<?php
/**
 * ::: DESCRIPTION HERE :::
 *
 * @author
 */
class m140125_192704_create_crm_socialnetworks_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('contract_social_networks', array(
            'id'                     => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'contractId'             => 'INT(11) UNSIGNED NOT NULL',
            'type'                   => 'tinyint(1) NOT NULL DEFAULT 0',
            'username'               => 'varbinary(255) NOT NULL DEFAULT ""',
            'password'               => 'varbinary(255) NOT NULL DEFAULT ""',
            'url'                    => 'varchar(255) NOT NULL DEFAULT ""',
            'advertise'              => 'tinyint(1) NOT NULL DEFAULT 0',
            'starRating'             => 'FLOAT(2,1) NOT NULL DEFAULT 0',
            'numReviews'             => 'INT(11) NOT NULL DEFAULT 0',
            'numFilteredReviews'     => 'INT(11) NOT NULL DEFAULT 0',
            'updatedAt'              => 'datetime NOT NULL',
            'createdAt'              => 'datetime NOT NULL',
        ));
    }

    public function down()
    {
        $this->dropTable('contract_social_networks');
    }
}