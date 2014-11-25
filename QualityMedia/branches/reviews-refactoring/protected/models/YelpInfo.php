<?php
/**
 * Model for handling business info in Yelp.
 *
 * Following properties are available
 * @property int $businessId
 * @property string $info
 * @property string $originalNodes
 * @property bool $saved
 * @property datetime $createdAt
 * @property datetime $updatedAt
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class YelpInfo extends ActiveRecord
{
    const STATUS_SAVED = 1;

    /**
     * @var array The info, this is used to verify all the details
     */
    public static $info_base = array(
        'basic_info' => array(
            // Yelp could've just implemented a stupid API, but nooooo....
            'additional_info' => array(
                'credit_cards' => null,
                'wheelchair_accessible' => null,
                'insurance' => null,
                'appointment_only' => null,
                'waiter_service' => null,
                'delivery' => null,
                'takeout' => null,
                'reservation' => null,
                'outdoor_seating' => null,
                'dogs_allowed' => null,
                'caters' => null,
                'alcohol' => null,
                'alcohol_detail' => array(
                    'full_bar' => null,
                    'beer_and_wine' => null,
                    'no' => null,
                    'none' => null,
                ),
                'wifi' => null,
                'wifi_detail' => array(
                    'free' => null,
                    'paid' => null,
                    'no' => null,
                    'none' => null,
                ),
                'parking' => null,
                'parking_detail' => array(
                    'valet' => null,
                    'garage' => null,
                    'street' => null,
                    'lot' => null,
                    'validated' => null,
                ),
                'smoking' => null,
                'smoking_detail' => array(
                    'no' => null,
                    'none' => null,
                    'outdoor' => null,
                    'yes' => null,
                ),
                'happy_hour' => null,
                'coat_check' => null,
            ),
            'address' => array(
                'city' => '',
                'line1' => '',
                'line2' => '',
                'zip' => '',
                'state' => '',
            ),
            'location' => array(),
            'name' => array(
                'biz_name' => '',
                'isLocked' => null,
            ),
            'phone' => '',
            'website' => '',
            'categories' => array(),
            'menu_url' => '',
        ),
        'hours' => array(),
        'specialties' => array(
            'speciality' => '',
        ),
        'history' => array(
            'history' => '',
            'year_established' => 0,
        ),
        'owner_info' => array(
            'bio' => '',
            'first_name' => '',
            'last_initial' => '',
            'photo' => '',
            'photo_options' => array(),
            'role' => '',
        ),
        'lockedAttributes' => array(),
    );

    /**
     * Returns the static instance for this model
     *
     * @static
     * @access public
     * @param string $className
     * @return YelpInfo
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns the table for this model
     *
     * @access public
     * @return string
     */
    public function tableName()
    {
        return 'yelp_info';
    }

    /**
     * Basic rules for validation
     *
     * @access public
     * @return array
     */
    public function rules()
    {
        return array(
            array('businessId, info, originalNodes', 'required'),
            array('businessId', 'exists', 'className' => 'User'),
            array('info', 'filter', 'filter' => 'trim'),
        );
    }

    /**
     * Defines our relations with other models
     *
     * @access public
     * @return array
     */
    public function relations()
    {
        return array(
            'business' => array(self::BELONGS_TO, 'User', 'businessId'),
        );
    }

    /**
     * Defines some basic scopes for this model
     *
     * @access public
     * @return array
     */
    public function scopes()
    {
        return array(
            'saved' => array(
                'condition' => 'saved = :saved',
                'params' => array(':saved' => self::STATUS_SAVED),
            ),
            'notSaved' => array(
                'condition' => 'saved != :saved',
                'params' => array(':saved' => self::STATUS_SAVED),
            ),
        );
    }

    /**
     * Removes a whole attribute from info
     *
     * @access public
     * @param string $attribute
     * @return void
     */
    public function removeInfoAttribute($attribute)
    {
        $info = $this->info;
        unset($info[$attribute]);
        $this->info = $info;
        $this->save();
    }

    /**
     * Called right before validation is done
     *
     * @access protected
     * @return bool
     */
    protected function beforeValidate()
    {
        if (!$this->validateInfo($this->info)) {
            return false;
        }

        $this->info = CJSON::Encode($this->info);
        $this->originalNodes = implode(',', $this->originalNodes);

        return parent::beforeValidate();
    }

    /**
     * Validates the info fields, makes sure every node present is valid
     *
     * @access protected
     * @param array $info
     * @param array $base
     * @return bool
     */
    protected function validateInfo(array $info, $base = array())
    {
        if (empty($base)) {
            $base = self::$info_base;
        }

        foreach ($info as $k => $v) {
            // This one was popping up everywhere, quick workaround for lockedAttributes
            if ($k == 'lockedAttributes') {
                continue;
            }

            if (!array_key_exists($k, $base)) {
                $this->addError('info', $k . ' is not a valid node');
                return false;
            }

            if (is_array($base[$k]) xor is_array($v)) {
                $this->addError('info', $k . ' is of invalid type');
                return false;
            }

            if (is_array($base[$k]) && !empty($base[$k])) {
                if (!$this->validateInfo($v, $base[$k])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Called right after a row is found
     *
     * @access protected
     * @return void
     */
    protected function afterFind()
    {
        $this->info = CJSON::Decode($this->info);
        $this->originalNodes = explode(',', $this->originalNodes);

        parent::afterFind();
    }

    /**
     * beforeSave hook, called right before a record is saved
     *
     * @access protected
     * @return bool
     */
    protected function beforeSave()
    {
        $this->saved = 0;
        return parent::beforeSave();
    }
}