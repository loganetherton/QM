<?php

class m130802_113235_alter_profiles_table_add_responsesWritten_field extends CDbMigration
{
    public $tableName = 'profiles';
    public $columnName = 'responsesWritten';

    public function up()
    {
        $this->addColumn($this->tableName, $this->columnName, 'INT(11) UNSIGNED NOT NULL DEFAULT 0');
        
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->columnName);
    }

}