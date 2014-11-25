<?php
/**
 * PhantomJs private message worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsPrivateMessageWorker extends PhantomJsWorker
{
    /**
     * Do the work.
     */
    public function doWork()
    {
        $id     = $this->data['id'];
        $model  = Message::model()->findByPk($id);

        if($model === null) {
            throw new WorkerPermanentFailureException("Message {$id} does no exist");
        }

        $profile     = $model->review->yelpBusiness->profile;
        $companyName = $model->review->yelpBusiness->label;
        $lockKey     = 'lock-' . $model->review->yelpBusiness->id;

        $this->lockBusiness($lockKey);

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $profile->yelpUsername,
            $profile->yelpPassword,
            $model->review->yelpBusiness->bizId,
            $model->review->reviewId,
            'private',
            $model->messageContent,
        ));

        $result = CJSON::decode($phantom->execute('Yelp_reply_to_review.js'));

        if(empty($result)) {
            throw new CException('Scraper response is empty');
        }

        if(isset($result['error'])) {
            $error = $result['error'];
            throw new CException("A private message to {$companyName} has not been posted (Scraper error: {$error})");
        }

        if(!empty($result) && isset($result['is_filtered'])) {
            $model->review->filtered = (int)(bool)empty($result['is_filtered']);
            $model->review->save();

            echo "Review {$model->review->id} has been moved to filtered pile\n";
        }

        echo "Private message to {$companyName} has been posted\n";

        $this->unlockBusiness($lockKey);
    }
}