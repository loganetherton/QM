<?php
/**
 * PhantomJs private message worker.
 * Although this worker does not require any method from PhantomJsPostingWorker,
 * it belongs to the group of posting workers.
 * That's the reason why this class extends PhantomJsPostingWorker instead of PhantomJsWorker directly.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class PhantomJsPrivateMessageWorker extends PhantomJsPostingWorker
{
    /**
     * @var object $message Message object.
     */
    protected $message;

    /**
     * @return object Message model.
     * @throws WorkerPermanentFailureException if message does not exist
     */
    protected function getMessageModel()
    {
        if($this->message === null) {
            $messageId = $this->data['id'];
            $this->message = Message::model()->findByPk($messageId);

            if($this->message === null) {
                throw new WorkerPermanentFailureException("Message does not exist ({$messageId})");
            }
        }

        return $this->message;
    }

    /**
     * @return object Review model
     * @throws WorkerPermanentFailureException if review does not exist
     */
    protected function getReviewModel()
    {
        return $this->getMessageModel()->review;
    }

    /**
     * @return integer yelp business id
     */
    protected function getYelpBusinessId()
    {
        return $this->getReviewModel()->yelpBusinessId;
    }

    /**
     * @return array Script-specific params
     */
    protected function getScriptParams()
    {
        return array(
            $this->getReviewModel()->reviewId,
            'private',
            $this->getMessageModel()->messageContent,
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
            // $message->review->filtered = (int)(bool)$result['is_filtered'];
            $review->save();

            if($review->filtered == 1) {
                printf("Review %s has been moved to filtered pile\n", $review->id);
            }
        }

        printf("Private message to %s has been posted\n", $this->getYelpBusinessModel()->label);
    }
}