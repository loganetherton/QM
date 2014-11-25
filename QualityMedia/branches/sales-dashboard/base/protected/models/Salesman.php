<?php
/**
 * This is the model class for table "salesmen".
 *
 * The followings are the available columns in table 'salesmen':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $firstName
 * @property string $lastName
 * @property string $lastVisit
 * @property string $createdAt
 * @property string $updatedAt
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Salesman extends User
{
    /**
     * @var integer $signups Number of signups.
     */
    public $signups = 0;

    /**
     * @var integer $revenues subscriptions total revenues.
     */
    public $revenues = 0;

    /**
     * @var string $usernameRegExp Username reg exp pattern.
     */
    protected $usernameRegExp = '/^[A-Za-z0-9-]+$/u';

    /**
     * Sort Order
     * @var string
     */
    public $sortOrder = 'DESC';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Salesman the static model class
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
        return 'salesmen';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('username', 'required'),
            array('username', 'length', 'max'=>100),
            array('username', 'match', 'pattern'=>$this->usernameRegExp),
            array('username', 'unique'),
            array('password', 'required', 'on'=>'insert'),
            array('firstName, lastName', 'safe'),
            array('fullName', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'users' => array(self::HAS_MANY, 'User', 'salesmanId'),
        );
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
            'lastVisit' => 'Last Visit',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Updated At',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
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
     * Primary keys array scope.
     * @param array $ids array of primary keys
     * @return object User
     */
    public function primaryKeysScope($ids = array())
    {
        if(count($ids)) {
            $criteria = new CDbCriteria;
            $criteria->addInCondition('t.id', array_map(function($id) {return (int) $id;}, $ids));

            $this->getDbCriteria()->mergeWith($criteria);
        }

        return $this;
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
     * Returns list of 10 sales reps with most subscriptions in last X days.
     * @param integer $days Days offset
     * @return array List of sales reps
     */
    public function getTopSalesReps($days = 30)
    {
        $offset = date('Y-m-d H:i:s', strtotime("now -{$days} days"));

        $criteria = new CDbCriteria;

        $criteria->with = array('users', 'users.subscriptions');
        $criteria->together = true;

        $criteria->select = array('t.*', 'SUM(IF(subscriptions.state = "active", 1, 0)) AS signups');

        if($days !== null) {
            $criteria->condition = 'users.salesmanId IS NOT NULL AND subscriptions.createdAt > :offset';
            $criteria->params = array(':offset'=>$offset);
        }
        else {
            $criteria->condition = 'users.salesmanId IS NOT NULL';
        }

        $criteria->group = 'users.salesmanId';
        $criteria->order = 'signups DESC';

        return $this->findAll($criteria);
    }

    /**
     * Returns list of 10 sales reps with most signups in last X days.
     * @param integer $days Days offset
     * @return array List of sales reps
     */
    public function getTopSalesRepsBySignups($days = 30)
    {
        $offset = date('Y-m-d H:i:s', strtotime("now -{$days} days"));

        $criteria = new CDbCriteria;

        $criteria->with = array('users');
        $criteria->together = true;

        $criteria->select = array('t.*', 'COUNT(users.id) AS signups');

        if($days !== null) {
            $criteria->condition = 'users.salesmanId IS NOT NULL AND users.createdAt > :offset';
            $criteria->params = array(':offset'=>$offset);
        }
        else {
            $criteria->condition = 'users.salesmanId IS NOT NULL';
        }

        $criteria->group = 'users.salesmanId';
        $criteria->order = 'signups DESC';

        return $this->findAll($criteria);
    }

    /**
     * Returns list of SalesReps with no subscriptions assigned
     * @return @return array List of sales reps
     */
    public function getSalesRepsWithoutSubscriptions()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array('users');
        $criteria->together = true;

        $criteria->select = array('t.*', 'COUNT(users.id) AS signups');
        $criteria->having = 'signups < 1';

        $criteria->group = 't.id';

        $dataProvider =  new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));

        $dataProvider->setTotalItemCount(count($this->findAll($criteria)));

        return $dataProvider;
    }
//Stats Functions

    public function getDefaultStatsCriteria()
    {
        return array(
            'sortOrder' => 'ASC',
            'dateFrom' => null,
            'dateTo' => null,
            'salesmenIds' => null
        );
    }

    /**
     * Returns stats by subscriptions count
     * @return array List of sales reps
     */
    public function getSaleRepsStatsBySubscriptions($criteria = array())
    {
        $params = array_merge($this->getDefaultStatsCriteria(), $criteria);

        $criteria = $this->getDbCriteria();
        $criteria->with = array('users', 'users.subscriptions');
        $criteria->together = true;

        $criteria->select = array('t.*', 'COUNT(subscriptions.id) AS signups');

        $criteria->condition = 'users.salesmanId IS NOT NULL';

        if($params['dateFrom']) {
            $criteria->addCondition("subscriptions.createdAt > '".date('Y-m-d', strtotime($params['dateFrom']))."'");
        }

        if($params['dateTo']) {
            $criteria->addCondition("subscriptions.createdAt <= '".date('Y-m-d', strtotime($params['dateTo'])+(3600*24))."'");
        }

        if(is_array($params['salesmenIds']) && count($params['salesmenIds'])) {
            $criteria->addInCondition("users.salesmanId",  $params['salesmenIds']);
        }

        $criteria->group = 'users.salesmanId';
        $criteria->order = 'signups '.$params['sortOrder'];

        $dataProvider = new CActiveDataProvider(get_class($this), array(
                'criteria'=>$criteria,
        ));

        $dataProvider->setPagination(false);
        return $dataProvider;
    }

    /**
     * Returns stats by revenues
     * @return array List of sales reps
     */
    public function getSaleRepsStatsByRevenues($criteria = array())
    {
        $params = array_merge($this->getDefaultStatsCriteria(), $criteria);

        $query = Yii::app()->db->createCommand()
        ->select("s.id, s.username, u.email, CAST(SUM(tr.amountInCents / 100) AS UNSIGNED) AS revenues, CONCAT(s.lastName, ', ', s.firstName) as fullName")
        ->from('salesmen s')
        ->join('users u', 's.id = u.salesmanId')
        ->join('transactions tr', 'tr.userId = u.id')
        ->join('subscriptions sb', 'sb.id = tr.subscriptionId')
        ->order('revenues '.$params['sortOrder'])
        ->group('s.id');

        $where = array('action = "purchase"', 'status = "success"');

        if($params['dateFrom']) {
            array_push($where, "sb.createdAt > '".date('Y-m-d', strtotime($params['dateFrom']))."'");
        }

        if($params['dateTo']) {
            array_push($where, "sb.createdAt <= '".date('Y-m-d', strtotime($params['dateTo'])+(3600*24))."'");
        }

        if(is_array($params['salesmenIds']) && count($params['salesmenIds'])) {
            array_push($where, "u.salesmanId IN (".implode(', ', $params['salesmenIds']).")");
        }

        if(count($where)) {
            $query->where(implode(' AND ', $where));
        }

        $result = $query->queryAll();

        $rows = new CArrayDataProvider(get_class($this), array(
            'data'=>$result,
        ));

        $rows->setPagination(false);
        return $rows;
    }

    public function getSaleRepsStatsByCancellations($criteria = array())
    {
        $params = array_merge($this->getDefaultStatsCriteria(), $criteria);

        $query = Yii::app()->db->createCommand()
        ->select("s.id, s.username, u.email, sb.id, CAST(COUNT(*) AS UNSIGNED) AS cancellations, CONCAT(s.lastName, ', ', s.firstName) AS fullName")
        ->from('salesmen s')
        ->join('users u', 's.id = u.salesmanId')
        ->join('subscriptions sb', "(sb.userId = u.id AND state = 'canceled')")
        ->order('cancellations '.$params['sortOrder'])
        ->group('s.id');

        $where = array();

        if($params['dateFrom']) {
            array_push($where, "sb.canceledAt > '".date('Y-m-d', strtotime($params['dateFrom']))."'");
        }

        if($params['dateTo']) {
            array_push($where, "sb.canceledAt <= '".date('Y-m-d', strtotime($params['dateTo'])+(3600*24))."'");
        }

        if(is_array($params['salesmenIds']) && count($params['salesmenIds'])) {
            array_push($where, "s.id IN (".implode(', ', $params['salesmenIds']).")");
        }

        if(count($where)) {
            $query->where(implode(' AND ', $where));
        }

        $result = $query->queryAll();

        $rows = new CArrayDataProvider(get_class($this), array(
            'data'=>$result,
        ));

        $rows->setPagination(false);
        return $rows;
    }
}