<?php
/**
 * This is helper model for private message answer.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class MessageAnswer extends Message
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return YelpReview the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Saves the current record.
     * @param boolean $runValidation Whether to perform validation before saving the record.
     * @param array $attributes List of attributes that need to be saved.
     * @return boolean whether the saving succeeds
     */
    public function save($runValidation = true, $attributes = null)
    {
        $transaction = $this->getDbConnection()->beginTransaction();

        try {
            $this->messageType  = 'message';
            $this->source       = self::SOURCE_DASHBOARD;
            $this->messageDate  = date('Y-m-d');

            if(!parent::save($runValidation, $attributes)) {
                throw new CDbException('Message has not been saved');
            }

            // Move to replied tab (without saving the model)
            $this->review->markAsReplied();

            // Move messages thread to sent folder
            $this->review->moveMessagesThreadToSent();

            // Update timestamps
            $this->review->latestMessageDate = date('Y-m-d H:i:s');
            $this->review->lastActionAt      = date('Y-m-d H:i:s');

            if(!$this->review->save()) {
                throw new CDbException('Review has not been saved');
            }

            $queueModel = new Queue;
            if(!$queueModel->addToQueue('privateMessage', array($this->id))) {
                throw new CDbException("Review {$this->id} failed while adding private message to queue");
            }

            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            Yii::log('Message answer error: ' . $e->getMessage(), CLogger::LEVEL_ERROR);
            $transaction->rollback();

            return false;
        }
    }
}