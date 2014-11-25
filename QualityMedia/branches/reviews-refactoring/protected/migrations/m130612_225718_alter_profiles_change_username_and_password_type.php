<?php
/**
 * Change "profiles" table username and password field type.
 * This change is required by encryption.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130612_225718_alter_profiles_change_username_and_password_type extends CDbMigration
{
    public $tableName = 'profiles';
    public $usernameColumn = 'yelpUsername';
    public $passwordColumn = 'yelpPassword';

    public function up()
    {
        $this->alterColumn($this->tableName, $this->usernameColumn, 'varbinary(100) NOT NULL');
        $this->alterColumn($this->tableName, $this->passwordColumn, 'varbinary(100) NOT NULL');
    }

    public function down()
    {
        $this->alterColumn($this->tableName, $this->usernameColumn, 'char(32) NOT NULL COLLATE utf8_general_ci');
        $this->alterColumn($this->tableName, $this->passwordColumn, 'char(32) NOT NULL COLLATE utf8_general_ci');
    }
}