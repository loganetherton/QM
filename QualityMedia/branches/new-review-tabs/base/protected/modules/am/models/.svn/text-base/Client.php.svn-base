<?php
/**
 * Model for individual Clients, mostly for note handling
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class Client extends User
{

    const STATUS_DISABLED    = 0;
    const STATUS_ENABLED     = 1;

    /**
     * @var Number of days to consider for flagged status
     */
    const FLAGGED_DAYS = 3;

    /**
     * @var string $companyName Used for searching
     */
    public $companyName;

    /**
     * @var string $character First character of the client's company name
     */
    public $letter;

    /**
     * Returns the static model for this class
     *
     * @static
     * @access public
     * @param string $className
     * @return Client
     */
    public static function model($className = __CLASS__)
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
            'notes' => array(self::HAS_MANY, 'Note', 'userId'),
            'info' => array(self::HAS_MANY, 'BizInfo', 'businessId'),
            'activity' => array(self::HAS_MANY, 'YelpAnalytics', 'businessId'),
            'photos' => array(self::HAS_MANY, 'Photo', 'businessId'),
            'yelpBusiness' => array(self::HAS_MANY, 'YelpBusiness', 'userId'),
        ));
    }

    /**
     * Returns the array containing validation rules for this model
     *
     * @access public
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('companyName', 'safe', 'on' => 'search'),
        ));
    }

    /**
     * Scope for specifying the accountManager
     *
     * @access public
     * @param int $accountManagerId
     * @return Client
     */
    public function accountManager($accountManagerId)
    {
        $this->getDbCriteria()->compare('t.accountManagerId', $accountManagerId);
        return $this;
    }

    /**
     * Returns the basic search scope for this model
     *
     * @access public
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->with = array(
            'profile' => array(
                'together' => true,
                'select' => 'yelpReviewsCount, yelpUsername, yelpId, twitterUserId, twitterOauthToken, twitterOauthSecret',
            ),
            'billingInfo' => array(
                'together' => true,
                'select' => 'companyName',
            ),
            'yelpBusiness' => array(
                'together' => true,
                'select' => 'label',
            ),
            'notes' => array(
                'scopes' => array('notArchived', 'isClientNote'),
                'with' => array(
                    'accountManager' => array(
                        'together' => true,
                    ),
                ),
                'order' => 'important DESC',
            ),

            // The following are just for figuring out whether we have
            // anything to show for the "View" link in table or not
            'photos' => array(
                'group'=>'yelpBusinessId',
                'limit' => 1,
            ),
            'info',
            'activity',
        );

        if ($this->companyName == '#') {
            $criteria->addCondition('LEFT(billingInfo.companyName, 1) NOT REGEXP :regex');
            $criteria->params = array(':regex' => '[A-Za-z]');
        }
        elseif (strlen($this->companyName) == 1) {
            $criteria->addCondition('billingInfo.companyName LIKE :like');
            $criteria->params = array(':like' => $this->companyName . '%');
        }
        else {
            $criteria->compare('billingInfo.companyName', $this->companyName, true);
            $criteria->compare('yelpBusiness.label', $this->companyName, true, 'OR');
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
            ),
            'sort' => array(
                'attributes' => array(
                    'createdAt' => array(
                        'asc' => 'profile.createdAt ASC',
                        'desc' => 'profile.createdAt DESC',
                    ),
                    'companyName' => array(
                        'asc' => 'billingInfo.companyName ASC',
                        'desc' => 'billingInfo.companyName DESC',
                    ),
                    'reviewsCount' => array(
                        'asc' => 'profile.yelpReviewsCount ASC',
                        'desc' => 'profile.yelpReviewsCount DESC',
                    ),
                ),
                'defaultOrder' => array(
                    'companyName' => CSort::SORT_ASC,
                ),
            ),
        ));
    }

    /**
     * Returns whether this client should be flagged or not
     *
     * @access public
     * @return bool
     */
    public function isFlagged()
    {
        $check_date = new DateTime();
        $check_date->sub(new DateInterval('P' . self::FLAGGED_DAYS . 'D'));

        // Check for the reviews, use the updatedAt column and see if we get anything
        $criteria = new CdbCriteria;
        $criteria->compare('updatedAt', '>' . $check_date->format('Y-m-d H:i:s'));
        $criteria->limit = 1;
        $reviewModel = Review::model();
        $reviewModel->getDbCriteria()->mergeWith($criteria);
        $review = $reviewModel->find();

        if (!is_null($review))
            return false;

        // So far we are being flagged, let's check the private messages
        $criteria = new CDbCriteria;
        $criteria->with = array(
            'review' => array(
                'condition' => 'businessId = :user',
                'params' => array(':user' => $this->id),
            ),
        );
        $criteria->compare('t.createdAt', '>' . $check_date->format('Y-m-d H:i:s'));
        $criteria->limit = 1;
        $messageModel = Message::model();
        $messageModel->getDbCriteria()->mergeWith($criteria);
        $message = $messageModel->find();

        if (!is_null($message))
            return false;

        return true;
    }

    /**
     * Returns the valid first characters available for the clients
     *
     * @access public
     * @return array
     */
    public function getValidFirstCharacters($userId = null)
    {
        if(!$userId){
            $userId = Yii::app()->getUser()->getId();
        }

        $userModel = AccountManager::model()->findByPk($userId);

        //!!! Couldn't get this to work with ActiveRecord
        if($userModel->showOnlyLinkedFeeds) {
            $characters = $this->getDbConnection()->createCommand('
                SELECT DISTINCT UPPER(LEFT(billing_info.companyName, 1))
                FROM users
                    INNER JOIN billing_info ON (billing_info.userId = users.id)
                WHERE users.accountManagerId = :acc')->queryColumn(array(':acc' => $userId));
        }
        else {
            $characters = $this->getDbConnection()->createCommand('
                SELECT DISTINCT UPPER(LEFT(billing_info.companyName, 1))
                FROM users
                    INNER JOIN billing_info ON (billing_info.userId = users.id)')->queryColumn();
        }

        foreach ($characters as $k => $character) {
            if (strlen(preg_replace('/[^A-Z]/', '', $character)) == 0) {
                $characters[] = '#';
                unset($characters[$k]);
            }
        }

        $characters = array_unique($characters);
        sort($characters);

        return $characters;
    }
}