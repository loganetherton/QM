<?php
/**
 * This is queue model for yelp businesses fetching purpose only.
 * It shares all Queue features.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ProfileQueue extends Queue
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Plan the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'profile_queues';
    }

    /**
     * Add a new task to the queue.
     * @return boolean Whether the saving succeeds
     */
    public function addToQueue($task, $params = array())
    {
        $this->setAttributes(array(
            'task'   => $task,
            'params' => CJSON::encode($params),
            'status' => self::STATUS_WAITING,
        ));

        return $this->save();
    }
}