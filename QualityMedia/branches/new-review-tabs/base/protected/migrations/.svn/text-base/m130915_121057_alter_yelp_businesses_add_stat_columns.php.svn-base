<?php
/**
 * Add "reviewsCount" and "responsesWritten" columns to "yelp_businesses" table.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m130915_121057_alter_yelp_businesses_add_stat_columns extends CDbMigration
{
    public $tableName = 'yelp_businesses';

    public $reviewsCountColumn = 'reviewsCount';
    public $responsesWrittenColumn = 'responsesWritten';

    public function up()
    {
        $this->addColumn($this->tableName, $this->reviewsCountColumn, 'SMALLINT(4) UNSIGNED NOT NULL AFTER `bizId`');
        $this->addColumn($this->tableName, $this->responsesWrittenColumn, 'SMALLINT(4) UNSIGNED NOT NULL AFTER `reviewsCount`');

        $this->execute("UPDATE {$this->tableName} yb
                        LEFT JOIN profiles p ON yb.profileId = p.id
                        SET
                            yb.{$this->reviewsCountColumn} = p.yelpReviewsCount,
                            yb.{$this->responsesWrittenColumn} = p.{$this->responsesWrittenColumn}");
    }

    public function down()
    {
        $this->dropColumn($this->tableName, $this->reviewsCountColumn);
        $this->dropColumn($this->tableName, $this->responsesWrittenColumn);
    }
}