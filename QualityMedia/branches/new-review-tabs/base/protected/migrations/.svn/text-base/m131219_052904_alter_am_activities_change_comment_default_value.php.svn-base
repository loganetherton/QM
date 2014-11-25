<?php
/**
 * Alter "am_activities" table by Adding default blank string value to comment columnt.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class m131219_052904_alter_am_activities_change_comment_default_value extends CDbMigration
{
    public $tableName = 'am_activities';
    public $columnName = 'comment';


    public function up()
    {
        $this->execute("ALTER TABLE  `".$this->tableName."` CHANGE  `".$this->columnName."` `comment` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';");
    }

    public function down()
    {
        $this->execute("ALTER TABLE  `".$this->tableName."` CHANGE  `".$this->columnName."` `comment` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;");
    }
}