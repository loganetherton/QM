<?php
/**
 * This is the model class for table "account_managers".
 *
 * The followings are the available columns in table 'account_managers':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $firstName,
 * @property string $lastName,
 * @property string $email,
 * @property int $showOnlyLinkedFeeds,
 * @property int $state,
 * @property string $lastVisit
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AccountManager extends User
{
    /**
     * @var string $usernameRegExp Username reg exp pattern.
     */
    protected $usernameRegExp = '/^[A-Za-z0-9_]+$/u';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AccountManager the static model class
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
        return 'account_managers';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('username, firstName, lastName', 'required'),
            array('username', 'length', 'max'=>100),
            array('username', 'match', 'pattern'=>$this->usernameRegExp),
            array('email, ', 'unique', 'on'=>'create', 'message'=>'Email has already been used.'),
            array('password', 'required', 'on'=>'insert'),
            array('fullName', 'safe', 'on'=>'search'),
            array('firstName, lastName, email, showOnlyLinkedFeeds, state', 'safe')
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'users' => array(self::HAS_MANY, 'User', 'accountManagerId'),
        );
    }

    /**
     * @return string User full name
     */
    public function getFullName($glue = ' ')
    {
        $fullName = $this->fullName;

        if(empty($fullName)) {
            $fullName = $this->lastName.$glue.$this->firstName;
        }
        return $fullName;;
    }

    /**
     * Searching Function
     * @return CActiveDataProvider query results
     */
    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->addSearchCondition('username',$this->username);
        $criteria->addSearchCondition('firstName',$this->username, true, 'OR');
        $criteria->addSearchCondition('lastName',$this->username, true, 'OR');
        $criteria->compare('CONCAT(lastName, " ", firstName)', $this->getFullName(), true);
        $criteria->compare('CONCAT(firstName, " ", lastName)', $this->getFullName(), true, 'OR');

        return new CActiveDataProvider(get_class($this), array(
                'criteria'=>$criteria,
        ));
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'salt' => 'Salt',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'email' => 'Email',
            'showOnlyLinkedFeeds' => 'Show Only Linked feeds',
            'state' => 'State',
            'lastVisit' => 'Last Visit',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * Find user by it's username.
     * @param string $username Username
     * @return ActiveRecord the record found. Null if none is found.
     */
    public function findByUsername($username)
    {
        return $this->findByAttributes(array('username'=>$username));
    }

    /**
     * @return integer New system notifications count
     */
    public function getSystemNotificationsCount($accountManagerId)
    {
        $systemNotificationModel = new SystemNotification;

        return $systemNotificationModel->unviewed()->accountManagerScope($accountManagerId)->count();
    }

    /**
     * @return integer New reviews count
     */
    public function getReviewsCount()
    {
        $reviewModel = new Review;

        if($this->showOnlyLinkedFeeds) {
            $reviewModel->businessScope($this->getClientIds());
        }

        return $reviewModel->notProcessed()->count();
    }

    /**
     * @return integer New messages count
     * @todo Export logic to Review class
     */
    public function getMessagesCount()
    {
        Yii::import('application.modules.am.models.AmReview');

        $reviewModel = new AmReview;

        if($this->showOnlyLinkedFeeds) {
            $reviewModel->businessScope($this->getClientIds());
        }

        return $reviewModel->pmInbox()->pmNotProcessed()->searchMessages()->getTotalItemCount();
    }

    /**
     * Returns list of Account Managers with no links to clients
     * @return ActiveRecord the record found. Null if none is found.
     */
    public function getManagersWithNoCLients()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('users');
        $criteria->together = true;

        $criteria->compare('showOnlyLinkedFeeds', 1);
        $criteria->select = array('t.*', 'COUNT(users.id) AS usersLinked');
        $criteria->having = 'usersLinked < 1';

        $criteria->group = 't.id';

        $dataProvider =  new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));

        $dataProvider->setTotalItemCount(count($this->findAll($criteria)));

        return $dataProvider;
    }

    /**
     * @return array An array with associated client ids
     */
    public function getClientIds()
    {
        $criteria = new CDbCriteria;
        $criteria->select = 'id';

        $clients = User::model()->accountManagerScope($this->id)->findAll($criteria);

        return CHtml::listData($clients, 'id', 'id');
    }

    /**
     * Returns an array with values for dropDown list
     * @param string $name Text to be displayed.
     * @return array Array with key => value pairs
     */
    public function dropDownList($name = 'username')
    {
        $criteria = new CDbCriteria;
        $criteria->select = array('id', 'username', 'firstName', 'lastName');

        if($name == 'fullName') {
            $criteria->order = 'lastName, firstName';

            $results = array();
            foreach($this->findAll($criteria) as $salesman) {
                $results[$salesman->id] = $salesman->getFullName(', ');
            }

            return $results;
        }
        else {
            $criteria->order = $name;

            return CHtml::listData($this->findAll($criteria), 'id', $name);
        }
    }
}