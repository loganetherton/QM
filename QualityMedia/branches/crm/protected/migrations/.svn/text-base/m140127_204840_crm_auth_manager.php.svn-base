<?php
/**
 * ::: DESCRIPTION HERE :::
 *
 * @author
 */
class m140127_204840_crm_auth_manager extends CDbMigration
{
    public function up()
    {
        $this->createTable('AuthItem', array(
            'name' => 'varchar(50) NOT NULL PRIMARY KEY',
            'type' => 'integer NOT NULL',
            'description' => 'text',
            'bizrule' => 'text',
            'data' => 'text',
        ));
        $this->createTable('AuthItemChild', array(
            'parent' => 'varchar(64) NOT NULL',
            'child' => 'varchar(64) NOT NULL',
            'PRIMARY KEY (`parent`, `child`)',
        ));
        $this->createTable('AuthAssignment', array(
            'itemname' => 'varchar(64) NOT NULL',
            'userid' => 'varchar(64) NOT NULL',
            'bizrule' => 'text',
            'data' => 'text',
            'PRIMARY KEY (itemname, userid)',
        ));

        $this->addForeignKey('parent', 'AuthItemChild', 'parent', 'AuthItem', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('child', 'AuthItemChild', 'child', 'AuthItem', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('itemname', 'AuthAssignment', 'itemname', 'AuthItem', 'name', 'CASCADE', 'CASCADE');

        $auth = Yii::app()->authManager;

        $auth->createOperation('viewSalesman', 'View existing salesmen');
        $auth->createOperation('manageSalesman', 'Create/edit salesmen');
        $auth->createOperation('deleteSalesman', 'Delete a Salesman');
        $auth->createOperation('viewContract', 'View existing contracts');
        $auth->createOperation('manageContract', 'Create/edit contracts');
        $auth->createOperation('deleteContract', 'Delete a contract');

        $salesmanRole = $auth->createRole('salesman');
        $salesmanRole->addChild('viewContract');
        $salesmanRole->addChild('manageContract');
        $salesmanRole->addChild('deleteContract');

        $adminRole = $auth->createRole('admin');
        $adminRole->addChild('viewSalesman');
        $adminRole->addChild('manageSalesman');
        $adminRole->addChild('deleteSalesman');

        $amRole = $auth->createRole('accountManager');
    }

    public function down()
    {
        $this->dropForeignKey('parent', 'AuthItemChild');
        $this->dropForeignKey('child', 'AuthItemChild');
        $this->dropForeignKey('itemname', 'AuthAssignment');

        $this->dropTable('AuthItem');
        $this->dropTable('AuthAssignment');
        $this->dropTable('AuthItemChild');
    }
}