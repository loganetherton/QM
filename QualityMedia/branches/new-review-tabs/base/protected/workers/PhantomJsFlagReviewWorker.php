<?php
/**
 * PhantomJs flag review worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsFlagReviewWorker extends PhantomJsWorker
{
    /**
     * Do the work.
     */
    public function doWork()
    {
        $id     = $this->data['id'];
        $model  = Review::model()->findByPk($id);

        if($model === null) {
            throw new WorkerPermanentFailureException("Review {$id} does not exist");
        }

        $profile     = $model->yelpBusiness->profile;
        $companyName = $model->yelpBusiness->label;
        $lockKey     = 'lock-' . $model->yelpBusiness->id;

        $this->lockBusiness($lockKey);

        $message            = $this->data['message'];
        $flagReasonCategory = $this->data['reasonCategory'];

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $profile->yelpUsername,
            $profile->yelpPassword,
            $model->yelpBusiness->bizId,
            $model->reviewId,
            'flag',
            $message,
            $flagReasonCategory
        ));

        $result = $this->getResult($phantom, 'Yelp_reply_to_review.js');

        echo "Review {$id} has been flagged\n";

        $this->unlockBusiness($lockKey);
    }
}