<?php
/**
 * Add relatedTweeterUserId field to tweets table
 *
 * @author Nitesh Pandey <nitesh@qualitymedia.com>
 */
class m130925_152518_alter_tweets_table_add_relatedTweeterUserId_column extends CDbMigration
{
    protected $table = 'tweets';
    
    protected $column = 'relatedTweeterUserId';
    
    protected $afterColumn = 'retweetedStatusId';
    
    public function up()
    {
        $this->addColumn($this->table, $this->column, sprintf('VARCHAR(30) NOT NULL AFTER `%s`', $this->afterColumn));
    }

    public function down()
    {
        $this->dropColumn($this->table, $this->column);
    }
}