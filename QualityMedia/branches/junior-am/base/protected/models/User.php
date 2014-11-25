<?php
/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table "users":
 * @property integer $id
 * @property integer $salesmanId
 * @property integer $accountManagerId
 * @property string $accountCode
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property Salesman $salesman
 * @property BillingInfo $billingInfo
 * @property Subscription[] $subscriptions
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class User extends ActiveRecord
{
    const DEFAULT_PASSWORD = 'qm1234';

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * @var integer $status Account status.
     */
    public $status;

    /**
     * @var string $fullName User full name.
     */
    public $fullName;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
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
        return 'users';
    }

    /**
     * @return array Validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('email', 'required'),
            array('email', 'length', 'max'=>100),
            array('email', 'email'),
            array('email', 'unique', 'on'=>'insert', 'message'=>'Email has already been used.'),
            array('email', 'filter', 'filter'=>'strtolower'),
            array('accountCode', 'unique', 'on'=>'create'),
            array('accountCode', 'filter', 'filter'=>'trim'),
            array('salesmanId', 'exist', 'className'=>'Salesman', 'attributeName'=>'id'),
            array('accountManagerId', 'exist', 'className'=>'AccountManager', 'attributeName'=>'id'),
            array('status', 'boolean'),
            array('fullName', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'billingInfo' => array(self::HAS_ONE, 'BillingInfo', 'userId'),
            'profile' => array(self::HAS_ONE, 'Profile', 'userId'),

            'salesman' => array(self::BELONGS_TO, 'Salesman', 'salesmanId'),
            'accountManager' => array(self::BELONGS_TO, 'AccountManager', 'accountManagerId'),

            'subscriptions' => array(self::HAS_MANY, 'Subscription', 'userId'),
            'notActiveSubscriptions' => array(self::HAS_MANY, 'Subscription', 'userId', 'condition'=>'notActiveSubscriptions.state !="active"'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'salesmanId' => 'Sales Rep',
            'accountManagerId' => 'Account Manager',
            'accountCode' => 'Account Code',
            'email' => 'Email',
            'password' => 'Password',
            'salt' => 'Salt',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Updated At',

            // Related attributes
            'salesman.username'=>'Sales rep.',
        );
    }

    /**
     * This method is invoked before saving a record (after validation, if any).
     * The default implementation raises the {@link onBeforeSave} event.
     * Use {@link isNewRecord} to determine whether the saving is
     * for inserting or updating record.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave()
    {
        // Hash password for new record
        if($this->getIsNewRecord() && $this->salt === null) {
            $this->salt = $this->encryptPassword(Text::random('alnum', 20), Text::random('alnum', 20));
            $this->password = $this->encryptPassword(self::DEFAULT_PASSWORD, $this->salt);
        }

        return parent::beforeSave();
    }

    /**
     * Deletes the row corresponding to this active record.
     * @return boolean whether the deletion is successful.
     * @throws CException if the record is new
     */
    public function delete()
    {
        throw new CException('User must not be deleted');
    }

    /**
     * Returns the declaration of named scopes.
     * @return array The scope definition.
     */
    public function scopes()
    {
        return array(
            'withSalesman'=>array(
                'with'=>'salesman',
                'together'=>true,
            ),
            'unassignedSalesman'=>array(
                'condition'=>'salesmanId IS NULL',
            ),
            'unassignedAccountManager'=>array(
                'condition'=>'accountManagerId IS NULL',
            ),
            'noSocialNetworkAssigned'=>array(
                'with'=>'profile',
                'condition'=>'profile.yelpId = "" OR profile.yelpId IS NULL',
            ),
        );
    }

    /**
     * Salesman scope.
     * @param integer $salesmanId Salesman id
     * @return object User
     */
    public function salesmanScope($salesmanId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'salesmanId = :salesman',
            'params'=>array(':salesman'=>$salesmanId),
        ));

        return $this;
    }

    /**
     * Account Manager scope.
     * @param integer $accountManagerId Account Manager id
     * @return object User
     */
    public function accountManagerScope($accountManagerId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'accountManagerId = :accountManager',
            'params'=>array(':accountManager'=>$accountManagerId),
        ));

        return $this;
    }

    /**
     * Primary keys array exclude scope.
     * @param array $ids array of primary keys
     * @return object User
     */
    public function excludePrimaryKeysScope($ids = array())
    {
        if(count($ids)) {
            $criteria = new CDbCriteria;
            $criteria->addNotInCondition('t.id', $ids);

            $this->getDbCriteria()->mergeWith($criteria);
        }

        return $this;
    }

    /**
     * Primary keys array scope.
     * @param array $ids array of primary keys
     * @return object User
     */
    public function primaryKeysScope($ids = array())
    {
        if(count($ids)) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('t.id', $ids);

            $this->getDbCriteria()->mergeWith($criteria);
        }

        return $this;
    }

    /**
     * Not linked to any Account Manager scope
     * @return object User
     */
    public function unlinkedScope()
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'accountManagerId IS NULL'
        ));

        return $this;
    }

    /**
     * Returns user by email (account code).
     * @param string $email Email
     * @return object CActiveRecord The record found. Null if none is found.
     */
    public function findByEmail($email)
    {
        return $this->findByAttributes(array('email'=>$email));
    }

    /**
     * Returns user by account code.
     * @param string $accountCode Account code
     * @param object CActiveDataProvider The record found. Null if none is found
     */
    public function findByAccountCode($accountCode)
    {
        return $this->findByAttributes(array('accountCode'=>$accountCode));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('billingInfo');
        $criteria->together = true;

        $criteria->compare('CONCAT(billingInfo.lastName, " ", billingInfo.firstName)',$this->fullName, true, 'OR');
        $criteria->compare('CONCAT(billingInfo.firstName, " ", billingInfo.lastName)',$this->fullName, true, 'OR');
        $criteria->compare('billingInfo.companyName',$this->fullName, true, 'OR');

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>array(
                    'createdAt'=>CSort::SORT_DESC,
                ),
                'attributes'=>array(
                    'fullName'=>array(
                        'asc'=>'billingInfo.lastName, billingInfo.firstName',
                        'desc'=>'billingInfo.lastName DESC, billingInfo.firstName DESC',
                    ),
                    'billingInfo.firstName'=>array(
                        'asc'=>'billingInfo.firstName',
                        'desc'=>'billingInfo.firstName DESC',
                    ),
                    'billingInfo.lastName'=>array(
                        'asc'=>'billingInfo.lastName',
                        'desc'=>'billingInfo.lastName DESC',
                    ),
                    'billingInfo.companyName'=>array(
                        'asc'=>'billingInfo.companyName',
                        'desc'=>'billingInfo.companyName DESC',
                    ),
                    'salesman.username'=>array(
                        'asc'=>'salesman.username',
                        'desc'=>'salesman.username DESC',
                    ),
                    '*',
                ),
            ),
        ));
    }

    /**
     * Encrypt password.
     * @param string $password password to encrypt
     * @param string $salt password salt
     * @return string encrypted password
     */
    public function encryptPassword($password, $salt)
    {
        return hash('sha512', sha1($salt.$password.$salt).$salt);
    }

    /**
     * @return string User full name
     */
    public function getFullName($glue = ' ')
    {
        if($this->billingInfo === null) {
            return '';
        }

        return $this->billingInfo->lastName.$glue.$this->billingInfo->firstName;
    }

    /**
     * @return string Salesman name
     */
    public function getSalesmanName($ifempty = '', $glue = ' ')
    {
        if($this->salesman === null) {
            return $ifempty;
        }

        return $this->salesman->getFullName($glue);
    }

    /**
     * @return string Account manager name
     */
    public function getAccountManagerName()
    {
        if($this->accountManager === null) {
            return '';
        }

        return $this->accountManager->getFullName();
    }

    /**
     * @return array List of available statuses
     */
    public function getStatuses()
    {
        return array(
            self::STATUS_ENABLED => 'Enabled',
            self::STATUS_DISABLED => 'Disabled',
        );
    }

    /**
     * @return string Formatted lastVisit attribute
     */
    public function getLastVisit()
    {
        return $this->lastVisit == '0000-00-00 00:00:00' ? 'Never' : Yii::app()->getComponent('format')->formatDateTime($this->lastVisit);
    }

    /**
     * Update lastVisit attribute.
     * @return boolean Whether lastVisit attribute has been saved
     */
    public function updateLastVisit()
    {
        return $this->saveAttributes(array(
            'lastVisit'=>new CDbExpression('NOW()')
        ));
    }

    /**
     * @return boolean Whether user has assigned a salesman.
     */
    public function hasSalesman()
    {
        return $this->salesmanId !== null;
    }

    /**
     * @return boolean Whether user has assigned an account manager.
     */
    public function hasAccountManager()
    {
        return $this->accountManagerId !== null;
    }

    /**
     * @return boolean Whether user has assigned a subscription.
     */
    public function hasSubscription()
    {
        return Subscription::model()->userScope($this->id)->exists();
    }

    /**
     * Returns list of Clients with no subscriptions assigned
     * @return ActiveRecord the record found. Null if none is found.
     */
    public function getClientsWithNoSubscriptions()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('subscriptions', 'billingInfo');

        $criteria->together = true;

        $criteria->select = array('t.*', 'COUNT(subscriptions.id) AS subscriptions');
        $criteria->having = 'subscriptions < 1';

        $criteria->group = 't.id';

        return new CActiveDataProvider(get_class($this), array(
                'criteria'=>$criteria,
        ));
    }

    /**
     * Returns list of Clients with no subscriptions assigned
     * @return ActiveRecord the record found. Null if none is found.
     */
    public function getClientsWithSubscriptionsWithoutSalesman()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('subscriptions', 'billingInfo');

        $criteria->together = true;

        $criteria->select = array('t.*', 'COUNT(subscriptions.id) AS subscriptions');
        $criteria->addCondition('salesmanId IS NULL');
        $criteria->having = 'subscriptions > 0';

        $criteria->group = 't.id';

        return new CActiveDataProvider(get_class($this), array(
                'criteria'=>$criteria,
        ));
    }

    /**
     * Handle recurly push notification.
     * @param object $event CEvent object
     * @return boolean True if everything is ok
     * @throws CDbException if model has not been saved
     */
    public function handlePushNotification($event)
    {
        // Get recurly account object
        $account = $event->sender->account;

        $model = $this->findByAccountCode($account->account_code);

        if($model === null) {
            $model = new self;
        }

        $model->setAttributes(array(
            'accountCode' => (string)$account->account_code,
            'email'=>empty($model->email) ? $account->account_code : $model->email,
        ));

        // Check if salesman's username has been sent using username field
        if(trim($account->username) != '') {
            $data = CJSON::decode($account->username);

            // Assign salesman (if sent and exists)
            if(isset($data['salesman'])) {
                $salesman = Salesman::model()->findByUsername($data['salesman']);

                if($salesman !== null) {
                    $model->salesmanId = $salesman->id;
                }

                unset($salesman);
            }

            // Assign account manager (if sent and exists)
            if(isset($data['accountManager'])) {
                $accountManager = AccountManager::model()->findByUsername($data['accountManager']);

                if($accountManager !== null) {
                    $model->accountManagerId = $accountManager->id;
                }

                unset($accountManager);
            }
        }

        if(!$model->save()) {
            Yii::log(var_export($model->getErrors(), true), CLogger::LEVEL_ERROR, 'push');
            throw new CDbException('User model has not been saved');
        }

        $event->params = array('user'=>$model);

        return true;
    }

    /**
     * Change password
     * @param string $newPassword New Password
     * @param string|null $oldPassword Old passowrd (optional)
     * @return boolean
     */
    public function changePassword($newPassword, $oldPassword = null)
    {
        if(null !== $oldPassword){
            if( $this->password !== $this->encryptPassword($oldPassword, $this->salt)){
                return FALSE;
            }
        }
        $this->salt = $this->encryptPassword(Text::random('alnum', 20), Text::random('alnum', 20));
        $this->password = $this->encryptPassword($newPassword, $this->salt);
        if($this->save()){
            return TRUE;
        }
        return FALSE;
    }
}