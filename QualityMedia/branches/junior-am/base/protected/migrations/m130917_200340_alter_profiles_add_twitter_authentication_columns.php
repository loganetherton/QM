<?php
/**
 * Adds twitterScreenName, twitterOauthToken, and twitterOauthSecret Columns to profiles table
 *
 * @author Nitesh <nitesh@qualitymedia.com>
 */
class m130917_200340_alter_profiles_add_twitter_authentication_columns extends CDbMigration
{
    public $tableName = 'profiles';

    public $columnName1 = 'twitterScreenName';
    public $columnName2 = 'twitterOauthToken';
    public $columnName3 = 'twitterOauthSecret';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName1, 'VARCHAR(255) NOT NULL AFTER `yelpPassword`');
        $this->addColumn($this->tableName, $this->columnName2, sprintf('VARCHAR(255) NOT NULL AFTER `%s`',$this->columnName1));
        $this->addColumn($this->tableName, $this->columnName3, sprintf('VARCHAR(255) NOT NULL AFTER `%s`',$this->columnName2));
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName1);
        $this->dropColumn($this->tableName, $this->columnName2);
        $this->dropColumn($this->tableName, $this->columnName3);
    }
}