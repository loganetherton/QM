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
 * @property int $type,
 * @property int $seniorManagerId,
 * @property int $state,
 * @property string $lastVisit
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AccountManager extends User
{
    const TYPE_JUNIOR = 0;
    const TYPE_SENIOR = 1;
    const TYPE_SUPER_SENIOR = 2;

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
            array('type, seniorManagerId', 'numerical', 'integerOnly'=>true),
            array('email, ', 'unique', 'on'=>'create', 'message'=>'Email has already been used.'),
            array('password', 'required', 'on'=>'insert'),
            array('fullName', 'safe', 'on'=>'search'),
            array('firstName, lastName, email, showOnlyLinkedFeeds, state, type, seniorManagerId', 'safe')
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'users' => array(self::HAS_MANY, 'User', 'accountManagerId'),
            'emailReport' => array(self::HAS_MANY, 'EmailReport', 'accountManagerId'),
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
        $criteria->mergeWith($this->getDbCriteria());

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
     * Returns the declaration of named scopes.
     * @return array The scope definition.
     */
    public function scopes()
    {
        return array(
            'unlinked'=>array(
                'condition' => 'seniorManagerId IS NULL OR seniorManagerId = 0'),
            'junior'  =>array(
                'condition' =>'type = '.self::TYPE_JUNIOR,
            ),
        );
    }

    /**
     * Not linked to any Account Manager scope
     * @return object User
     */
    public function unlinkedScope()
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'seniorManagerId IS NULL OR seniorManagerId = 0'
        ));

        return $this;
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
     * Find account managers by assigned senior AM's id.
     * @param string $username Username
     * @return ActiveRecord the record found. Null if none is found.
     */
    public function findBySeniorManagerId($seniorManagerId)
    {
        $criteria=new CDbCriteria;
        $criteria->compare('seniorManagerId',$seniorManagerId);

        return new CActiveDataProvider(get_class($this), array(
                'criteria'=>$criteria,
        ));
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
     * @return array An array with associated client id / Company Name
     */
    public function clientsDropdownList()
    {
        $model = User::model();

        if($this->showOnlyLinkedFeeds) {
             $model->accountManagerScope($this->id);
        }

        $criteria = new CDbCriteria;
        $criteria->select = 'id';
        $criteria->with = 'billingInfo';
        $criteria->order = 'billingInfo.companyName';

        $clients = $model->findAll($criteria);

        return CHtml::listData($clients, 'id', 'billingInfo.companyName');
    }

    /**
     * @return array An array with associated Junior Account Managers
     */
    public function getLinkedAccountManagers()
    {
        $criteria = new CDbCriteria;

        switch ($this->type) {
            case self::TYPE_SENIOR:
                $criteria->compare('seniorManagerId', $this->id);
            break;
            case self::TYPE_SUPER_SENIOR:
                $criteria->condition='id != '.$this->id.' AND type = '.self::TYPE_JUNIOR;
            break;
        }

        return new CActiveDataProvider(get_class($this), array(
                'criteria' => $criteria,
        ));
    }

    public function getSeniorManager()
    {
        return $this->seniorManagerId != null ? self::model()->findByPk($this->seniorManagerId) : null;
    }

    public function linkedAmsDropDownList($name = 'userName')
    {
        $criteria = new CDbCriteria;

        switch ($this->type) {
            case self::TYPE_SENIOR:
                $criteria->compare('seniorManagerId', $this->id);
            break;
            case self::TYPE_SUPER_SENIOR:
                $criteria->condition='id != '.$this->id.' AND type = '.self::TYPE_JUNIOR;
            break;
        }

        if($name == 'fullName') {
            $criteria->order = 'lastName, firstName';

            $results = array();
            foreach($this->findAll($criteria) as $salesman) {
                $results[$salesman->id] = $salesman->getFullName(', ');
            }

        }
        else {
            $criteria->order = $name;

            $results = CHtml::listData($this->findAll($criteria), 'id', $name);
        }

        return $results;
    }

    /**
     * Returns an array with values for dropDown list
     * @param string $name Text to be displayed.
     * @return array Array with key => value pairs
     */
    public function dropDownList($name = 'username', $type = null)
    {
        $criteria = new CDbCriteria;
        $criteria->select = array('id', 'username', 'firstName', 'lastName');

        //Account Manager type filter
        switch($type) {
            case 'junior':
                $criteria->addCondition('type = '.self::TYPE_JUNIOR);
            break;
            case 'senior':
                $criteria->addCondition('type = '.self::TYPE_SENIOR);
            break;
            case 'supersenior':
                $criteria->addCondition('type = '.self::TYPE_SUPER_SENIOR);
            break;
            case 'seniors':
                $criteria->addCondition('type = '.self::TYPE_SENIOR);
                $criteria->addCondition('type = '.self::TYPE_SUPER_SENIOR, 'OR');
            break;
        }

        if($name == 'fullName') {
            $criteria->order = 'lastName, firstName';

            $results = array();
            foreach($this->findAll($criteria) as $salesman) {
                $results[$salesman->id] = $salesman->getFullName(', ');
            }

        }
        else {
            $criteria->order = $name;

            $results = CHtml::listData($this->findAll($criteria), 'id', $name);
        }

        return $results;
    }

    /**
     * Returns Account Manager types list
     * @return array Array of type
     */
    public function getTypes($fullDesc = true)
    {
        $types = array(
            self::TYPE_JUNIOR       => 'Junior',
            self::TYPE_SENIOR       => 'Senior',
            self::TYPE_SUPER_SENIOR => 'Super Senior'.( $fullDesc ? ' (linked to all Junior Ams)' : ''),
        );

        return $types;
    }

    /**
     * Check if the AM is senior or not
     * @return boolean true if the user is senior, otherwise false
     */
    public function isSenior()
    {
        return ($this->type == AccountManager::TYPE_SENIOR || $this->type == AccountManager::TYPE_SUPER_SENIOR);
    }

    /**
     * Check if the AM is Super Senior or not
     * @return boolean true if the user is Super Senior, otherwise false
     */
    public function isSuperSenior()
    {
        return ($this->type == AccountManager::TYPE_SUPER_SENIOR);
    }

    /**
     * Returns Account Manager
     * @return [type] [description]
     */
    public function getTypeLabel($fullDesc = true)
    {
        $types = $this->getTypes($fullDesc);
        return $types[$this->type];
    }
}