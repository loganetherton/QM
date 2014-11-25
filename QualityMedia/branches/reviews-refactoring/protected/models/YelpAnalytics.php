<?php
/**
 * Model for handling anayltical info in Yelp.
 *
 * Following properties are available
 * @property int $id
 * @property int $businessId
 * @property string $info
 * @property datetime $createdAt
 * @property datetime $updatedAt
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class YelpAnalytics extends ActiveRecord
{
    /**
     * Returns the static instance for this model
     *
     * @static
     * @access public
     * @param string $className
     * @return YelpAnalytic
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns the table name for this model
     *
     * @access public
     * @return string
     */
    public function tableName()
    {
        return 'yelp_analytics';
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
            array('businessId, info', 'required'),
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
     * Called right before validation is done
     *
     * @access protected
     * @return bool
     */
    protected function beforeValidate()
    {
        $this->info = CJSON::Encode($this->info);

        return parent::beforeValidate();
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

        parent::afterFind();
    }
}