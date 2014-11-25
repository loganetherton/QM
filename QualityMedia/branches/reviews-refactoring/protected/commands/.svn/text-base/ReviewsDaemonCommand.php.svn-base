<?php
/**
 * Reviews daemon command.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ReviewsDaemonCommand extends DaemonCommand
{
    /**
     * @var string $pidFile PID file name.
     */
    public $pidFile = 'reviews.pid';

    /**
     * Default action.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria;

        $criteria->select = array('userId', 'yelpId', 'yelpUsername', 'yelpPassword');

        $criteria->addCondition('yelpId <> ""');
        $criteria->addCondition('yelpUsername <> ""');
        $criteria->addCondition('yelpPassword <> ""');

        $criteria->order = 'userId';

        $businesses = Profile::model()->findAll($criteria);

        $this->log(sprintf('Number of businesses to process: %d', count($businesses)));

        // Flush logs (so they can be visible before script ends it's job)
        Yii::getLogger()->flush(true);

        $path = dirname(__FILE__).'/../yiic phantomJs';
        foreach($businesses as $business) {
            $this->log(sprintf('    User: %d, bizId: %s', $business->userId, $business->yelpId));

            $this->log('Reviews');
            exec("{$path} readReviews {$business->userId}", $result);
            $this->log(implode("\n", $result));

            $this->log('Messages');
            exec("{$path} readMessages {$business->userId} sent", $result);
            $this->log(implode("\n", $result));

            // Flush logs (so they can be visible before script ends it's job)
            Yii::getLogger()->flush(true);
        }
    }
}