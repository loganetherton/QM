<?php
/**
 * This is the model class for table "profiles".
 *
 * The followings are the available columns in table 'profiles':
 * @property integer $id
 * @property integer $userId
 * @property string $yelpId
 * @property string $yelpUsername
 * @property string $yelpPassword
 * @property integer $yelpReviewsCount
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property User $user
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class Profile extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Profile the static model class
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
        return 'profiles';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('yelpId, yelpUsername, yelpPassword', 'safe'),
            array('userId', 'exist', 'className'=>'User', 'attributeName'=>'id'),
            array('yelpId', 'length', 'max'=>100),
            array('yelpReviewsCount', 'numerical', 'integerOnly' => true),
            array('yelpId, yelpUsername, yelpPassword', 'filter', 'filter'=>'trim'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'userId' => 'User',
            'yelpId' => 'Yelp',
            'yelpUsername' => 'Yelp Username',
            'yelpPassword' => 'Yelp Password',
            'yelpReviewsCount' => 'Yelp Reviews',
            'createdAt' => 'Date Added',
            'updatedAt' => 'Updated At',
        );
    }

    /**
     * This method is invoked before saving a record (after validation, if any).
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave()
    {
        // Sometimes yelpId contains search query (biz-id#query:searched biz) that needs to be removed
        $parsedUrl = parse_url($this->yelpId);
        if($parsedUrl !== false) {
            $this->yelpId = $parsedUrl['path'];
        }

        if(!empty($this->yelpUsername)) {
            $this->encryptUsername('yelp', $this->yelpUsername);
        }

        if(!empty($this->yelpPassword)) {
            $this->encryptPassword('yelp', $this->yelpPassword);
        }

        return parent::beforeSave();
    }

    /**
     * This method is invoked after each record is instantiated by a find method.
     * Make sure you call the parent implementation so that the event is raised properly.
     */
    protected function afterFind()
    {
        parent::afterFind();

        if(!empty($this->yelpUsername)) {
            $this->yelpUsername = $this->decryptUsername('yelp');
        }

        if(!empty($this->yelpPassword)) {
            $this->yelpPassword = $this->decryptPassword('yelp');
        }
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns decrypted username.
     * @param string $socialNetwork Social network name
     * @return string Decrypted username
     * @throws CException if social network is not supported
     */
    public function decryptUsername($socialNetwork)
    {
        switch($socialNetwork) {
            case 'yelp':
                return Yii::app()->getSecurityManager()->decrypt($this->yelpUsername);
                break;
            default:
                throw new CException("{$socialNetwork} is not supported");
                break;
        }
    }

    /**
     * Sets encrypted username.
     * @param string $socialNetwork Social network name
     * @param string $username Username
     * @throws CException if social network is not supported
     */
    public function encryptUsername($socialNetwork, $username)
    {
        $username = Yii::app()->getSecurityManager()->encrypt($username);

        switch($socialNetwork) {
            case 'yelp':
                $this->yelpUsername = $username;
                break;
            default:
                throw new CException("{$socialNetwork} is not supported");
                break;
        }
    }

    /**
     * Returns decrypted password.
     * @param string $socialNetwork Social network name
     * @return string Decrypted password
     * @throws CException if social network is not supported
     */
    public function decryptPassword($socialNetwork)
    {
        switch($socialNetwork) {
            case 'yelp':
                return Yii::app()->getSecurityManager()->decrypt($this->yelpPassword);
                break;
            default:
                throw new CException("{$socialNetwork} is not supported");
                break;
        }
    }

    /**
     * Sets encrypted passwords.
     * @param string $socialNetwork Social network name
     * @param string $password Password
     * @throws CException if social network is not supported
     */
    public function encryptPassword($socialNetwork, $password)
    {
        $password = Yii::app()->getSecurityManager()->encrypt($password);

        switch($socialNetwork) {
            case 'yelp':
                $this->yelpPassword = $password;
                break;
            default:
                throw new CException("{$socialNetwork} is not supported");
                break;
        }
    }

    /**
     * Find by yelp id.
     * @param string $yelpId Yelp business id
     * @return object The record found. Null if none is found
     */
    public function findByYelpId($yelpId)
    {
        return $this->findByAttributes(array('yelpId'=>$yelpId));
    }
}