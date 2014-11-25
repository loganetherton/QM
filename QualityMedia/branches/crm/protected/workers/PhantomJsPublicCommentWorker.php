<?php
/**
 * PhantomJs public comment worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsPublicCommentWorker extends PhantomJsWorker
{
    /**
     * Do the work.
     */
    public function doWork()
    {
        $id     = $this->data['id'];
        $model  = Review::model()->findByPk($id);

        if($model === null) {
            throw new WorkerPermanentFailureException("Review {$id} does no exist");
        }

        $profile     = $model->yelpBusiness->profile;
        $companyName = $model->yelpBusiness->label;
        $lockKey     = 'lock-'.$model->yelpBusiness->id;

        $this->lockBusiness($lockKey);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $profile->yelpUsername,
            $profile->yelpPassword,
            $model->yelpBusiness->bizId,
            $model->reviewId,
            'public',
            $model->publicCommentContent,
        ));

        $result = CJSON::decode($phantom->execute('Yelp_reply_to_review.js'));

        if(empty($result)) {
            throw new CException('Scraper response is empty');
        }

        if(isset($result['error'])) {
            $error = $result['error'];
            throw new CException("A public comment to {$companyName} has not been posted (Scraper error: {$error})");
        }

        if(!empty($result) && isset($result['is_filtered'])) {
            $model->filtered = (int)(bool)$result['is_filtered'];
            $model->save();

            echo "Review {$id} has been moved to filtered pile\n";
        }

        echo "Public comment to {$companyName} has been posted\n";

        $this->unlockBusiness($lockKey);
    }
}