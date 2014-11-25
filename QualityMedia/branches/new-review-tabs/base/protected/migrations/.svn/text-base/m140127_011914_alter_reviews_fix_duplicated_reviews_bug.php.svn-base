<?php
/**
 * Fix duplicated reviews bug.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class m140127_011914_alter_reviews_fix_duplicated_reviews_bug extends CDbMigration
{
    public $tableName = 'reviews';

    public function up()
    {
        // Get duplicates
        $sql = 'SELECT reviewId FROM reviews WHERE deleted = 0 GROUP BY reviewId HAVING COUNT(id) > 1 ORDER BY reviewId';
        $duplicates = $this->getDbConnection()->createCommand($sql)->queryColumn();

        // Setup data provider and pagination (single query may be too large)
        $dataProvider = new CArrayDataProvider($duplicates);

        $pagination = new CPagination($dataProvider->getTotalItemCount());
        $pagination->setPageSize(1000);

        $dataProvider->setPagination($pagination);

        // Run the queries in turns
        for($i = 0; $i < $pagination->getPageCount(); $i++) {
            $pagination->setCurrentPage($i);

            $ids = array();
            foreach($dataProvider->getData($refresh = true) as $reviewId) {
                $ids[] = "'{$reviewId}'";
            }

            $ids = implode(', ', $ids);

            // deleted = 2 is not supported in the application, but it does it's job and is easy to revert in case of an error
            $sql = "UPDATE reviews
                    LEFT JOIN (
                        SELECT IF(r1.updatedAt >= r2.updatedAt, r2.id, r1.id) AS id
                        FROM reviews r1
                        LEFT JOIN reviews r2 ON r1.reviewId = r2.reviewId AND r1.id <> r2.id
                        WHERE r1.reviewId IN ({$ids})
                        GROUP BY r1.reviewId
                    ) r ON reviews.id = r.id
                    SET deleted = 2
                    WHERE r.id IS NOT NULL;";

            $this->execute($sql);
        }
    }

    public function down()
    {
        $this->update($this->tableName, array('deleted'=>new CDbExpression(0)), 'deleted = :deleted', array(':deleted'=>'2'));
    }
}