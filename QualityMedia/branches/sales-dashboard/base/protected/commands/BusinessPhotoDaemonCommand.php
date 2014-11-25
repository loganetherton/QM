<?php
/**
 * Business analytics daemon command.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class BusinessPhotoDaemonCommand extends DaemonCommand
{
    /**
     * @var string $pidFile PID file name.
     */
    public $pidFile = 'business-photo.pid';

    /**
     * Default action.
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria;

        $criteria->select = array('t.id', 't.userId', 't.yelpId', 't.bizId');
        $criteria->with = array(
            'profile'=>array(
                'select'    => array('yelpUsername', 'yelpPassword'),
                'together'  => true,
            ),
        );

        $criteria->addCondition('t.yelpId <> ""');
        $criteria->addCondition('t.bizId <> ""');
        $criteria->addCondition('profile.yelpUsername <> ""');
        $criteria->addCondition('profile.yelpPassword <> ""');

        $criteria->order = 't.userId';

        $businesses = YelpBusiness::model()->findAll($criteria);

        $this->log(sprintf('Number of businesses to process: %d', count($businesses)));

        // Flush logs (so they can be visible before script ends it's job)
        Yii::getLogger()->flush(true);

        $path = dirname(__FILE__).'/../yiic phantomJs';
        foreach($businesses as $business) {
            $this->log(sprintf('    User: %d, bizId: %s, yelpId: %s', $business->userId, $business->bizId, $business->yelpId));

            exec("{$path} readPhotos {$business->id}", $result);
            $this->log(implode("\n", $result));

            // Flush logs (so they can be visible before script ends it's job)
            Yii::getLogger()->flush(true);
        }
    }
}