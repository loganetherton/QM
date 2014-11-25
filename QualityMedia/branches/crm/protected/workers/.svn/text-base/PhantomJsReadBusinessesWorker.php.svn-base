<?php
/**
 * PhantomJs read businesses worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsReadBusinessesWorker extends PhantomJsWorker
{
    /**
     * Do the work.
     */
    public function doWork()
    {
        $id      = $this->daat['id'];
        $profile = Profile::model()->findByPk($id);

        if($profile === null) {
            throw new WorkerPermanentFailureException("Profile with provided id ({$id}) does not exist");
        }

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $profile->yelpUsername,
            $profile->yelpPassword,
        ));

        $result = CJSON::decode($phantom->execute('yelp_authenticate_login.js'));

        if(empty($result)) {
            throw new CException('Scraper response is empty');
        }

        if(isset($result['error'])) {
            $error = $result['error'];
            throw new CException("Businesses for {$id} have not been fetched (Scraper error: {$error})");
        }

        Yii::import('application.modules.api.models.YelpBusinessesReceiver');

        $model = new YelpBusinessesReceiver;
        $model->setProfile($profile);
        $model->setAttributes($result);

        if(!$model->save()) {
            $error = var_export($model->getErrors(), true);
            throw new CException("Businesses for {$id} have not been saved (Database error: {$error})");
        }

        echo "Businesses for {$id} have been fetched\n";
    }
}