<?php
/**
 * Change fields names in "yelp_message_threads" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130618_000133_alter_yelp_message_threads_change_field_names extends CDbMigration
{
    public $tableName = 'yelp_message_threads';

    public $columns = array(
        'threadYelpId'  => 'thread',
        'userYelpId'    => 'userId',
        'userImageUrl'  => 'userPhotoLink',
    );

    public function up()
    {
        foreach($this->columns as $oldName => $newName) {
            $this->renameColumn($this->tableName, $oldName, $newName);
        }
    }

    public function down()
    {
        foreach($this->columns as $oldName => $newName) {
            $this->renameColumn($this->tableName, $newName, $oldName);
        }
    }
}