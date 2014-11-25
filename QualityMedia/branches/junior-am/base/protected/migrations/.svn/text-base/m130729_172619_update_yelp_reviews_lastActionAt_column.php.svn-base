<?php
/**
 * Populates lastActionAt column for old records
 *
 * @author Nitesh Pandey <nitesh@nitesh.com.np>
 */
class m130729_172619_update_yelp_reviews_lastActionAt_column extends CDbMigration
{
    public function up()
    {
        $sql = 'UPDATE `yelp_reviews`
                LEFT JOIN (SELECT * FROM `yelp_messages` ORDER BY `messageDate` DESC) AS ym
                ON `ym`.`reviewId`=`yelp_reviews`.`id`
                SET lastActionAt = IF(
                    ym.id IS NULL,
                    publicCommentDate,
                    IF(ym.createdAt > yelp_reviews.publicCommentDate, ym.createdAt, yelp_reviews.publicCommentDate)
                )';

        $this->execute($sql);
    }

    public function down()
    {
    }
}