<?php
/**
 * Yelp message receiver.
 * This model is for API use only.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class YelpMessageReceiver extends CFormModel
{
    /**
     * @var array $bizId Yelp business id.
     */
    protected $bizId;

    /**
     * @var array $messages An array of sent messages.
     */
    protected $messages = array();

    /**
     * @var array $threads Private message threads.
     */
    protected $threads = array();

    /**
     * Sets the attribute values in a massive way.
     * @param array $values attribute values (name=>value) to be set.
     * @param boolean $safeOnly This param is here for compatibility purposes only.
     */
    public function setAttributes($values, $safeOnly=true)
    {
        $this->bizId = $values['business_id'];

        foreach($values['messages'] as $message) {
            $this->threads[] = $message['thread_id'];

            $attributes = array(
                'thread'        => $message['thread_id'],
                'userId'        => $message['user_yelp_id'],
                'userName'      => $message['user_name'],
                'userPhotoLink' => $message['user_image_url'],
                'subject'       => $message['message_subject'],
                'excerpt'       => $message['message_excerpt'],
            );

            $attributes['messages'] = array();

            foreach($message['conversations'] as $conversation) {
                if($conversation['type'] == 'comment') {
                    continue;
                }

                $content = trim($conversation['message']['content']);
                $time = $this->parseTime($conversation['message']['time']);

                $attributes['conversations'][] = array(
                    'from'          => $conversation['from'],
                    'userId'        => $message['user_yelp_id'],
                    'type'          => $conversation['type'],
                    'message'       => $content,
                    'messageHash'   => sha1($message['user_yelp_id'].$content),
                    'messageDate'   => date('Y-m-d', $time),
                );
            }

            $this->messages[] = $attributes;
        }
    }

    /**
     * Save reviews.
     * @return boolean Whether all reviews have been saved
     */
    public function save()
    {
        $transaction = Yii::app()->getDb()->beginTransaction();

        try {
            $profile = Profile::model()->findByYelpId($this->bizId);

            if($profile === null) {
                throw new CException($this->bizId.' does not exist in the database');
            }

            $reviews = $this->getReviews();

            foreach($this->messages as $message) {
                $model = $this->getThreadModel($message['thread']);

                // Set attributes
                $model->businessId  = $profile->userId;
                $model->reviewId    = isset($reviews[$message['thread']]) ? $reviews[$message['thread']] : null;
                $model->setAttributes($message);

                if(!$model->save()) {
                    throw new CException(sprintf('Thread %s has not been saved', $message['thread']));
                }

                foreach($message['conversations'] as $conversation) {
                    $messageModel = $this->getMessageModel($conversation['messageHash']);

                    // Don't overwrite from attribute is message has been sent by staff
                    if($messageModel->isStaffAnswer()) {
                        unset($conversation['from']);
                    }

                    // Set attributes
                    $messageModel->threadId = $model->id;
                    $messageModel->reviewId = isset($reviews[$message['thread']]) ? $reviews[$message['thread']] : null;
                    $messageModel->source   = YelpMessage::SOURCE_YELP;
                    $messageModel->setAttributes($conversation);

                    if(!$messageModel->save()) {
                        throw new CException(sprintf('%s message has not been saved', $conversation['messageHash']));
                    }
                }
            }

            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR);
            $transaction->rollback();

            return false;
        }
    }

    /**
     * Get YelpMessageThread model.
     * @param string $thread Message thread id
     * @return object YelpMessageThread
     */
    protected function getThreadModel($thread = null)
    {
        if($thread === null) {
            return new YelpMessageThread;
        }

        $model = YelpMessageThread::model()->findByThread($thread);

        return $model === null ? new YelpMessageThread : $model;
    }

    /**
     * Get YelpMessage model.
     * @param string $hash Message hash
     * @return object YelpMessage
     */
    protected function getMessageModel($hash)
    {
        if($hash === null) {
            return new YelpMessage;
        }

        $model = YelpMessage::model()->findByMessageHash($hash);

        return $model === null ? new YelpMessage : $model;
    }

    /**
     * This method returns list of privateConversationThread => reviewId pairs.
     * They are being used to create a relation between review and private conversation.
     * @return array List of reviews
     */
    protected function getReviews()
    {
        $criteria = new CDbCriteria;

        $criteria->select = array('id', 'privateConversationThread');
        $criteria->order = 'privateConversationThread';

        $criteria->addInCondition('privateConversationThread', $this->threads);

        return CHtml::listData(YelpReview::model()->findAll($criteria), 'privateConversationThread', 'id');
    }

    /**
     * Parse yelp relative timestamp.
     * @param string $time Yelp time value
     * @return integer Timestamp
     */
    public function parseTime($time)
    {
        $time = str_replace('Updated - ', '', $time);
        $time = str_replace(array('one', 'One'), '1', $time);

        return strtotime($time);
    }
}