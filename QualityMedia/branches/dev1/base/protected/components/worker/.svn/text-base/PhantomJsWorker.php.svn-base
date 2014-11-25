<?php
/**
 * Base class for all phantomjs related workers.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
abstract class PhantomJsWorker extends Worker
{
    /**
     * @var object $yelpBusiness YelpBusiness model.
     */
    protected $yelpBusiness;

    /**
     * @var array $errors List of available errors.
     */
    protected $errors = array(
        // Permanent errors
        'biz_blocked_by_user'       => array('failure'=>'permanent', 'message'=>'This customer has asked not to be contacted by business owners'),
        'blank_reply_content'       => array('failure'=>'permanent', 'message'=>'Blank reply content'),
        'forbidden_access_messages' => array('failure'=>'permanent', 'message'=>'Forbidden messages access'),
        'incomplete_data'           => array('failure'=>'permanent', 'message'=>'Incomplete data'),
        'no_biz_access_error'       => array('failure'=>'permanent', 'message'=>'There is no business available'),
        'review_already_flagged'    => array('failure'=>'permanent', 'message'=>'Review already flagged'),
        'review_already_replied'    => array('failure'=>'permanent', 'message'=>'Review already replied'),
        'review_not_found'          => array('failure'=>'permanent', 'message'=>'Review not found'),
        'slideshow_present'         => array('failure'=>'permanent', 'message'=>'Slideshow present'),

        // Long term errors (one needs some time to fix them)
        'account_not_verified'      => array('failure'=>'long', 'message'=>'Account not verified'),

        // Medium term errors (24h should be enough to fix them)
        'captcha_error'             => array('failure'=>'medium', 'message'=>'Verification code (captcha) occured'),
        'daily_limit_reached'       => array('failure'=>'medium', 'message'=>'Daily limit reached'),
        'incorrect_password'        => array('failure'=>'medium', 'message'=>'Incorrect password'),
        'invalid_owner_photo'       => array('failure'=>'medium', 'message'=>'Invalid owner photo'),
        'photo_not_found'           => array('failure'=>'medium', 'message'=>'Photo not found'),
        'update_name_error'         => array('failure'=>'medium', 'message'=>'Update business name'),
        'upload_owner_photo'        => array('failure'=>'medium', 'message'=>'Upload owner photo'),

        // Short term errors (usually some temp issue)
        'logged_out'                => array('failure'=>'short', 'message'=>'Logged out'),
        'missing_login_page'        => array('failure'=>'short', 'message'=>'Missing login page (Yelp site is down)'),
        'no_network'                => array('failure'=>'short', 'message'=>'No network (Temp network issues)'),
        'request_timed_out'         => array('failure'=>'short', 'message'=>'Request timed out'),
    );

    /**
     * Return the number of seconds this worker needs hold a lease on the task for while it performs work.
     * @return integer Number of seconds this task needs to remain leased for
     */
    public function getRequiredLeaseTime()
    {
        return 1800;    // 30 minutes
    }

    /**
     * Return the number of seconds a task should wait after a failure before retrying.
     * @param object $task Task object. This object is probably
     * useful mostly to examine the failure count if you want to implement staggered retries.
     * @return integer|null Number of seconds to wait between retries,
     * or null for a default retry period (currently 60 seconds).
     */
    public function getWaitBeforeRetry($task)
    {
        $exception = $task->getExecutionException();

        if($exception instanceof WorkerException) {
            return $exception->getProrogation();
        }

        // Use required lease time by default
        return $this->getRequiredLeaseTime();
    }

    /**
     * @return object YelpBusiness model
     * @throws WorkerPermanentFailureException if yelp business does not exist
     */
    protected function getYelpBusinessModel()
    {
        if($this->yelpBusiness === null) {
            $yelpBusinessId = $this->getYelpBusinessId();
            $this->yelpBusiness = YelpBusiness::model()->findByPk($yelpBusinessId);

            if($this->yelpBusiness === null) {
                throw new WorkerPermanentFailureException("Yelp business does not exist ({$yelpBusinessId})");
            }
        }

        return $this->yelpBusiness;
    }

    /**
     * @return integer Yelp business id
     */
    protected function getYelpBusinessId()
    {
        return $this->data['id'];
    }

    /**
     * @return object Profile model
     */
    protected function getProfileModel()
    {
        return $this->getYelpBusinessModel()->profile;
    }

    /**
     * Do the actual work.
     */
    final public function doWork()
    {
        // We do this outside of the try-catch because we don't want to unlock the locked business.
        // We would do it because of the exception in lockBusiness method.
        $this->lockBusiness();

        try {
            $result = $this->executeScript();
            $this->processPhantomJsResult($result);

            $this->unlockBusiness();
        }
        catch(Exception $e) {
            $this->unlockBusiness();

            throw $e;
        }

        return $result;
    }

    /**
     * @return array Script common params (username, password, bizId)
     */
    protected function getCommonParams()
    {
        $profile = $this->getProfileModel();

        return array(
            $profile->yelpUsername,
            $profile->yelpPassword,
            $this->getYelpBusinessModel()->bizId,
        );
    }

    /**
     * Execute the script and get the result.
     * @return array Scraper result
     * @throws Worker exceptions
     */
    protected function executeScript()
    {
        $params = array_merge($this->getCommonParams(), $this->getScriptParams());

        $phantom = Yii::app()->getComponent('phantomjs');
        $phantom->setParams($params);

        $result = $phantom->execute($this->getScriptName());
        $result = CJSON::decode($result);

        if(empty($result)) {
            throw new WorkerShortFailureException('Scraper response is empty');
        }

        if(isset($result['status']) && $result['status'] == 'error') {
            throw new CException('Unknown scraper error');
        }

        // Handle PhantomJs scraper errors
        if(isset($result['error'])) {
            $error = $result['error'];

            if(in_array($error, array_keys($this->errors))) {
                $message = $this->errors[$error]['message'];
                $failure = $this->errors[$error]['failure'];

                switch($failure) {
                    case 'permanent':
                        throw new WorkerPermanentFailureException($message);
                        break;
                    case 'long':
                        throw new WorkerLongFailureException($message);
                        break;
                    case 'medium':
                        throw new WorkerMediumFailureException($message);
                        break;
                    case 'short':
                        throw new WorkerShortFailureException($message);
                        break;
                }
            }

            // Throw a default error
            throw new CException($error);
        }

        return $result;
    }

    /**
     * Process PhantomJs script result.
     * @param mixed $result Script execution result
     */
    protected function processPhantomJsResult($result)
    {
        // Do nothing by default
    }

    /**
     * @return string Business lock key
     */
    protected function getLockKey()
    {
        return 'lock-' . $this->getYelpBusinessId();
    }

    /**
     * Create a per-business lock to prevent daemons from loggin each other out.
     * @return boolean True on success
     * @throws PhantomJsBusinessLockException if business is already locked
     */
    protected function lockBusiness()
    {
        $lockKey = $this->getLockKey();

        if(!Yii::app()->getComponent('lock')->add($lockKey, '', $this->getRequiredLeaseTime())) {
            // Store lock in cache if cache does not contain a lock for this business yet.
            // Otherwise throw an exception and stop task from being executed

            $message = "Other daemon is currently processing another task for this business ({$lockKey})";
            throw new PhantomJsBusinessLockException($message);
        }

        return true;
    }

    /**
     * Unlock business so other tasks for the businesses can be processed.
     * This usually means that task is done or failed.
     * @return boolean If no error happens during deletion
     */
    protected function unlockBusiness()
    {
        return Yii::app()->getComponent('lock')->delete($this->getLockKey());
    }

    /**
     * @return array Script-specific params
     */
    abstract protected function getScriptParams();

    /**
     * @return string Script name
     */
    abstract protected function getScriptName();
}