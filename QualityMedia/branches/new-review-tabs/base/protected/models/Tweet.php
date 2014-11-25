<?php
/**
 * This is the model class for table "tweets".
 *
 * The followings are the available columns in table 'tweets':
 * @property integer $id
 * @property string $statusId
 * @property string $rawStatus
 * @property string $text
 * @property string $twitterUserId
 * @property string $inReplyToStatusId
 * @property bool $retweeted 
 * @property integer $favoriteCount
 * @property integer $retweetCount
 * @property string $retweetedStatusId
 * @property string $user
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @author Nitesh Pandey <nitesh@qualitymedia.com>
 */

class Tweet extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return YelpBusiness the static model class
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
        return 'tweets';
    }
    
    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('statusId, rawStatus, text, twitterUserId, user', 'required'),
            array('retweeted', 'boolean'),
        );
    }
    
    
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'statusId' => 'Status ID',
            'rawStatus' => 'Raw Status',
            'text' => 'Text',
            'twitterUserId' => 'Twitter User ID',
            'retweetCount' => 'Retweet Count',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        );
    }
    
    /**
     * Called right after a row is found
     *
     * @access protected
     * @return void
     */
    protected function afterFind()
    {
        $this->user = CJSON::decode($this->user);

        parent::afterFind();
    }
}