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
     * @return array Script-specific params
     */
    protected function getScriptParams()
    {
        return array();     // No additional params are required
    }

    /**
     * @return string Script name
     */
    protected function getScriptName()
    {
        return 'yelp_read_reviews_with_messages.js';
    }

    /**
     * Process PhantomJs script result.
     * @param mixed $result Script execution result
     */
    protected function processPhantomJsResult($result)
    {
        $companyName = $this->getYelpBusinessModel()->label;

        Yii::import('application.modules.api.models.YelpTotalReviewReceiver');

        $model = new YelpTotalReviewReceiver;
        $model->setAttributes($result);

        if(!$model->save()) {
            $error = var_export($model->getErrors(), true);
            throw new CException("Reviews for {$companyName} have not been saved (Database error: {$error})");
        }

        echo "Reviews for {$companyName} have been saved\n";
    }
}