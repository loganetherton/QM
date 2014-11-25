<?php
/**
 * Queue businesses.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class QueueBusinessesCommand extends CConsoleCommand
{
    /**
     * Queue reviews.
     */
    public function actionReview()
    {
        $this->queueTasks('PhantomJsReadReviewsWorker');
    }

    /**
     * Queue activity stats.
     */
    public function actionAnalytics()
    {
        $this->queueTasks('PhantomJsReadAnalyticsWorker');
    }

    /**
     * Queue tasks.
     * @param string $worker Worker name
     */
    protected function queueTasks($worker)
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

        foreach($businesses as $business) {
            $model = new WorkerActiveTask;
            $model->setAttributes(array(
                'taskName'  => $worker,
                'data'      => CJSON::encode(array('id'=>$business->id)),
            ));

            printf("[%10s] %s\n", $business->id, ($model->save() ? 'Saved' : 'Not saved'));;
        }
    }
}