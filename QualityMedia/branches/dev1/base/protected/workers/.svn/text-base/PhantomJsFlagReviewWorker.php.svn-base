<?php
/**
 * PhantomJs flag review worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsFlagReviewWorker extends PhantomJsPostingWorker
{
    /**
     * @return array Script-specific params
     */
    protected function getScriptParams()
    {
        return array(
            $this->getReviewModel()->reviewId,
            'flag',
            $this->data['message'],
            $this->data['reasonCategory'],
        );
    }

    /**
     * @return string Script name
     */
    protected function getScriptName()
    {
        return 'Yelp_reply_to_review.js';
    }

    /**
     * Process PhantomJs script result.
     * @param mixed $result Script execution result
     */
    protected function processPhantomJsResult($result)
    {
        printf("Review %s has been flagged\n", $this->data['id']);
    }
}