<?php
/**
 * This is the model class for table "admins".
 *
 * The followings are the available columns in table 'admins':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $firstName
 * @property string $lastName
 * @property string $fullName
 * @property string $last_visit
 * @property string $created_at
 * @property string $updated_at
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Admin extends User
{
    /**
     * @var string $usernameRegExp Username reg exp pattern.
     */
    protected $usernameRegExp = '/^[A-Za-z0-9_]+$/u';

    /**
     * Returns the static model of the specified AR class.
     * @return Admin the static model class
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
        return 'admins';
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
            array('username, fullName', 'safe', 'on'=>'search'),
            array('firstName, lastName', 'safe'),
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
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Id',
            'username' => 'Username',
            'password' => 'Password',
            'salt' => 'Salt',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'lastVisit' => 'Last Visit',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Updated At',
        );
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

        return new CActiveDataProvider(get_class($this), array(
                'criteria'=>$criteria,
        ));
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
}