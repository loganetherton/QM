<?php
/**
 * Create "subscriptions" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130423_111529_create_subscriptions_table extends CDbMigration
{
    public $tableName = 'subscriptions';

    public $userFK = 'subscriptions_users_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'userId'        => 'INT(11) UNSIGNED NOT NULL',
            'uuid'          => 'CHAR(32) NOT NULL COLLATE utf8_general_ci',
            'planCode'      => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci',
            'planName'      => 'VARCHAR(200) NOT NULL COLLATE utf8_general_ci',
            'state'         => 'VARCHAR(10) NOT NULL COLLATE utf8_general_ci',
            'unitAmount'    => 'INT(6) NOT NULL',
            'quantity'      => 'SMALLINT(5) UNSIGNED NOT NULL',
            'currency'      => 'CHAR(3) NOT NULL COLLATE utf8_general_ci',
            'activatedAt'   => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'canceledAt'    => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'expiresAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'currentPeriodStartedAt'    => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'currentPeriodEndsAt'       => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'trialStartedAt'=> 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'trialEndsAt'   => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'createdAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->userFK, $this->tableName, 'userId', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}