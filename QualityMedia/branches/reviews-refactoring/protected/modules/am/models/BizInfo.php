<?php
/**
 * Model for Business info
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class BizInfo extends YelpInfo
{
    /**
     * Returns the static instance for this model
     *
     * @static
     * @access public
     * @param string $className
     * @return BizInfo
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Search scope for getting business info
     *
     * @access public
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->with = array(
            'business' => array(
                'together' => true,
                'with' => 'billingInfo',
            ),
        );

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}