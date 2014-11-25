<?php
/**
 * Create "profiles" table.
 * Profiles table will be used to store clients' social network profiles.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130611_110920_create_profiles_table extends CDbMigration
{
    public $tableName = 'profiles';

    public $userFK = 'users_profiles_fk';

    public function up()
    {
        $this->createTable($this->tableName, array(
            'id'            => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'userId'        => 'int(11) UNSIGNED NOT NULL',
            'yelpId'        => 'varchar(100) NOT NULL COLLATE utf8_general_ci',
            'yelpUsername'  => 'char(32) NOT NULL COLLATE utf8_general_ci',
            'yelpPassword'  => 'char(32) NOT NULL COLLATE utf8_general_ci',
            'createdAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
            'updatedAt'     => 'timestamp DEFAULT "0000-00-00 00:00:00"',
        ), 'ENGINE = InnoDB CHARSET = utf8;');

        $this->addForeignKey($this->userFK, $this->tableName, 'userId', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey($this->userFK, $this->tableName);

        $this->dropTable($this->tableName);
    }
}