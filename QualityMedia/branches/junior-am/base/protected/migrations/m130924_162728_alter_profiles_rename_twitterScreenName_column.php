<?php
/**
 * Changes twitterScreenName column to twitterUserId
 *
 * @author Nitesh Pandey <nitesh@qualitymedia.com>
 */
class m130924_162728_alter_profiles_rename_twitterScreenName_column extends CDbMigration
{
    protected $table = 'profiles';
    
    protected $oldName = 'twitterScreenName';
    
    protected $newName = 'twitterUserId';
    
    public function up()
    {
        $this->renameColumn($this->table, $this->oldName, $this->newName);
    }

    public function down()
    {
        $this->renameColumn($this->table, $this->newName, $this->oldName);
    }
}