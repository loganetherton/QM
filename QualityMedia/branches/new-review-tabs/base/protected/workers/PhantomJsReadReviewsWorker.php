<?php
/**
 * PhantomJs read reviews worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsReadReviewsWorker extends PhantomJsWorker
{
    /**
     * Return the number of seconds this worker needs hold a lease on the task for while it performs work.
     * @return integer Number of seconds this task needs to remain leased for
     */
    public function getRequiredLeaseTime()
    {
        return 3600;    // 60 minutes
    }

    /**
     * Do the work.
     */
    public function doWork()
    {
        $id = $this->data['id'];
        $yelpBusiness = YelpBusiness::model()->withCredentials()->findByPk($id);

        if($yelpBusiness === null) {
            throw new WorkerPermanentFailureException("Yelp business with provided id ({$id}) does not exist");
        }

        $companyName = $yelpBusiness->label;
        $lockKey     = 'lock-'.$yelpBusiness->id;

        $this->lockBusiness($lockKey);

        echo "Fetching reviews for {$companyName} ({$yelpBusiness->id})...\n";

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $yelpBusiness->profile->yelpUsername,
            $yelpBusiness->profile->yelpPassword,
            $yelpBusiness->bizId,
        ));

        $result = $this->getResult($phantom, 'yelp_read_reviews_with_messages.js');

        // TEMP DEBUG CODE
        $this->saveScraperResult($result);

        Yii::import('application.modules.api.models.YelpTotalReviewReceiver');

        $model = new YelpTotalReviewReceiver;
        $model->setAttributes($result);

        if(!$model->save()) {
            $error = var_export($model->getErrors(), true);
            throw new CException("Reviews for {$companyName} have not been saved (Database error: {$error})");
        }

        echo "Reviews for {$companyName} have been saved\n";

        $this->unlockBusiness($lockKey);
    }

    /**
     * !! THIS IS JUST A TEMP CODE FOR DEBUGING !!
     * Save scraper result in a file.
     */
    protected function saveScraperResult($result)
    {
        $path = sprintf('%s/reviews-json/%s', Yii::app()->getRuntimePath(), $result['business_id']);
        self::mkdir($path, array(), true);

        $filename = rtrim($path, '/') . '/' . date('YmdHis') . '.json';

        file_put_contents($filename, CJSON::encode($result));
    }

    /**
     * Shared environment safe version of mkdir. Supports recursive creation.
     * For avoidance of umask side-effects chmod is used.
     *
     * @static
     * @param string $dst path to be created
     * @param array $options newDirMode element used, must contain access bitmask.
     * @param boolean $recursive
     * @return boolean result of mkdir
     * @see mkdir
     */
    protected static function mkdir($dst, array $options, $recursive)
    {
        $prevDir=dirname($dst);
        if($recursive && !is_dir($dst) && !is_dir($prevDir))
            self::mkdir(dirname($dst),$options,true);

        $mode=isset($options['newDirMode']) ? $options['newDirMode'] : 0777;
        $res=mkdir($dst, $mode);
        chmod($dst,$mode);
        return $res;
    }
}