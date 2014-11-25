<?php
/**
 * Wrapper for Review model for Account Manager module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AmReview extends Review
{
    /**
     * @var string $companyName Company name search attribute.
     */
    public $companyName;

    /**
     * @var string $lastPrivateReplyPostingStatus Shows last reply posting status
     */
    public $lastPrivateReplyPostingStatus;

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
     * Returns the relations, extends them to include note
     *
     * @access public
     * @return array
     */
    public function relations()
    {
        return array_merge(parent::relations(), array(
            'notes' => array(self::HAS_MANY, 'Note', 'reviewId'),
        ));
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->lastPrivateReplyPostingStatus = $this->getLastPrivateReplyPostingStatus();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return CMap::mergeArray(parent::rules(), array(
            array('companyName, id', 'safe', 'on'=>'search'),
        ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = $this->getDbCriteria();

        $criteria->with = array(
            'user'=>array(
                'select'=>'id',
                'together'=>true,
            ),
            'user.billingInfo'=>array(
                'select'=>'companyName, contractDate',
                'together'=>true,
            ),
            'yelpBusiness',
            'updates',
            'privateMessages'=>array(
                'with'=>'review',
            ),
            'notes' => array(
                'scopes' => array('notArchived', 'isReviewNote'),
                'with' => array(
                    'accountManager' => array(
                        'together' => true,
                    ),
                ),
                'order' => 'important DESC',
            ),
        );

        //Criteria
        // $criteria->compare('user.status', 1);
        $criteria->compare('yelpBusiness.label', $this->companyName, true);
        $criteria->compare('yelpBusiness.status', 1);
        $criteria->compare('t.id', $this->id);

        $result = new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.lastActionAt DESC, t.id ASC',
                'attributes'=>array(
                    'companyName'=>array(
                        'asc'=>'companyName ASC',
                        'desc'=>'companyName DESC',
                    ),
                    'approvalStatus'=> array(
                        'asc' => 'FIELD(t.totalApprovalStatus, 0, 1, 3, 2) ASC, t.lastActionAt DESC, t.id ASC',
                        'desc' => 'FIELD(t.totalApprovalStatus, 0, 1, 3, 2) DESC, t.lastActionAt DESC, t.id ASC',
                    ),
                    'flagApprovalStatus'=> array(
                        'asc' => 'FIELD(t.flagApprovalStatus, 0, 1, 3, 2) ASC, t.lastActionAt DESC, t.id ASC',
                        'desc' => 'FIELD(t.flagApprovalStatus, 0, 1, 3, 2) DESC, t.lastActionAt DESC, t.id ASC',
                    ),
                    '*',
                )
            ),
        ));

        $result->setTotalItemCount($this->count($criteria));

        return $result;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function searchMessages()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array(
            'user',
            'user.billingInfo'=>array(
                'select'=>'companyName',
                'together'=>true,
            ),
            'yelpBusiness',
            'privateMessages'=>array(
                'with'=>'review',
            ),
            'privateMessagesSearch'=>array(
                'select'=>false,
                'together'=>true,
            ),
        );

        $criteria->compare('user.status', 1);
        $criteria->compare('yelpBusiness.label', $this->companyName, true);
        $criteria->compare('yelpBusiness.status', 1);
        $criteria->addNotInCondition('privateMessagesSearch.messageContent', array(null));

        $criteria->group = 't.id';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.latestMessageDate DESC, t.id ASC',
            ),
        ));
    }

    /**
     * Returns the status of yelp posting for the last private reply
     * @return integer
     */
    public function getLastPrivateReplyPostingStatus()
    {
        //Sort messages starting from the latest and reset offset keys
        $privateMessages = array_reverse(array_values($this->privateMessages));

        $postingStatus = 0;

        //Find the last reply message
        if(count($privateMessages)) {
            $message = $privateMessages[0];

            $postingStatus = $message->postingStatus;
        }

        return $postingStatus;
    }
}