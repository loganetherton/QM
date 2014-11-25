<?php
/**
 * PhantomJs public comment worker.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsPublicCommentWorker extends PhantomJsPostingWorker
{
    /**
     * @return array Script-specific params
     */
    protected function getScriptParams()
    {
        $review = $this->getReviewModel();

        return array(
            $review->reviewId,
            'public',
            $review->publicCommentContent,
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
        $review = $this->getReviewModel();

        if(!empty($result) && isset($result['is_filtered'])) {
            $review->filtered = (int)($result['is_filtered'] == 'true');    // [!!] It's a string value, not bool
            $review->save();

            if($review->filtered) {
                printf("Review %s has been moved to filtered pile\n", $this->data['id']);
            }
        }

        printf("Public comment to %s has been posted\n", $this->getYelpBusinessModel()->label);
    }
}