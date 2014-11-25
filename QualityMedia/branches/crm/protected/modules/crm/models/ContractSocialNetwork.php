<?php
/**
 * Model for handling Social Networks linked to a contract
 *
 * @author Shitiz Garg <shitiz@qualitymedia.com>
 *
 * @property int $id Social Network's ID
 * @property int $contractId The linked contract's ID
 * @property int $type The social network's type
 * @property string $username Username/oAuth token for this social network
 * @property string $password Password/oAuth secret for this social network
 * @property string $url Reference URL for this social network
 * @property int $advertise
 * @property int $starRating
 * @property int $numReviews
 * @property int $numFilteredReviews
 */

class ContractSocialNetwork extends ActiveRecord
{
    const TYPE_YELP        = 0;
    const TYPE_TWITTER     = 1;
    const TYPE_FACEBOOK    = 2;
    const TYPE_GPLUS       = 3;
    const TYPE_TRIPADVISOR = 4;
    const TYPE_FOURSQUARE  = 5;
    const TYPE_SERVICEEMAIL= 6;
    const TYPE_SOCIALSTAR  = 7;

    /**
     * Returns this AR model's static instance
     *
     * @access public
     * @param string $className
     * @return SocialNetwork
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns this AR's linked DB table
     *
     * @access public
     * @return string
     */
    public function tableName()
    {
        return 'contract_social_networks';
    }

    /**
     * Returns rules for validation upon data entry/change for this model
     *
     * @access public
     * @return array
     */
    public function rules()
    {
        return array(
            array('contractId, url', 'required'),
            array('contractId', 'exist', 'className' => 'Contract', 'attributeName' => 'id'),
            array('username, password, url', 'filter', 'filter' => 'trim'),
            array('advertise', 'boolean'),
            array('type', 'in', 'range' => array_keys(self::getValidTypes())),
            array('type, advertise, starRating, numReviews, numFilteredReviews',
                    'numerical', 'integerOnly' => true),
            // Conditionally require username, password if fields is one of
            // Yelp, Foursquare, TripAdvisor and Twitter
            array(
                'username, password',
                'application.components.validators.EConditionalValidator',
                'conditionalRules' => array(
                    'type', 'in',
                    'range' => array(
                        self::TYPE_FOURSQUARE,  self::TYPE_TWITTER,
                        self::TYPE_TRIPADVISOR, self::TYPE_YELP,
                    ),
                ),
                'rule' => array('required'),
            ),
        );
    }

    /**
     * Defines this AR model's relations with other models
     *
     * @access public
     * @return array
     */
    public function relations()
    {
        return array(
            'contract' => array(self::BELONGS_TO, 'Contract', 'contractId'),
        );
    }

    /**
     * Event run right before a record is saved, encrypts username/password
     *
     * @access public
     * @return mixed
     */
    public function beforeSave()
    {
        $this->username = $this->encrypt($this->username);
        $this->password = $this->encrypt($this->password);

        return parent::beforeSave();
    }

    /**
     * Event run right after a record is found, decrypts username/password
     *
     * @access public
     * @return mixed
     */
    public function afterFind()
    {
        $this->username = $this->decrypt($this->username);
        $this->password = $this->decrypt($this->password);

        return parent::afterFind();
    }

    /**
     * Returns valid types for a social network
     *
     * @access public
     * @return array
     */
    public function getValidTypes()
    {
        return array(
            self::TYPE_YELP         => 'Yelp',
            self::TYPE_FACEBOOK     => 'Facebook',
            self::TYPE_TWITTER      => 'Twitter',
            self::TYPE_GPLUS        => 'Google+',
            self::TYPE_TRIPADVISOR  => 'TripAdvisor',
            self::TYPE_SERVICEEMAIL => '@Connect',
            self::TYPE_SOCIALSTAR   => 'SocialStar',
            self::TYPE_FOURSQUARE   => 'Foursquare',
        );
    }

    /**
     * Encrypts the given string (generally username/password)
     *
     * @access public
     * @param string $string
     * @return string
     */
    public function encrypt($string)
    {
        return Yii::app()->getSecurityManager()->encrypt($string);
    }

    /**
     * Decrypts the given string (generally username/password)
     *
     * @access public
     * @param string $string
     * @return string
     */
    public function decrypt($string)
    {
        return Yii::app()->getSecurityManager()->decrypt($string);
    }
}