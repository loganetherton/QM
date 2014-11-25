<?php
/**
 * Base class for all posting phantomjs workers.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
abstract class PhantomJsPostingWorker extends PhantomJsWorker
{
    /**
     * @var object $review Review model.
     */
    protected $review;

    /**
     * @return object Review model
     * @throws WorkerPermanentFailureException if review does not exist
     */
    protected function getReviewModel()
    {
        if($this->review === null) {
            $reviewId = $this->getReviewId();
            $this->review = Review::model()->findByPk($reviewId);

            if($this->review === null) {
                throw new WorkerPermanentFailureException("Review does not exist ({$reviewId})");
            }
        }

        return $this->review;
    }

    /**
     * @return integer Review id
     */
    protected function getReviewId()
    {
        return $this->data['id'];   // Use data id by default
    }

    /**
     * @return integer yelp business id
     */
    protected function getYelpBusinessId()
    {
        return $this->getReviewModel()->yelpBusinessId;
    }
}