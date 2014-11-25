<?php
/**
 * Yelp's Photos controller for Account manager module
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class Photo extends YelpPhoto
{
    /**
     * Returns the static instance for this mdoel
     *
     * @static
     * @access public
     * @param string $className
     * @return Photo
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Search scope for account manager index
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
        $criteria->order = 't.fromOwner ASC, t.uploaded DESC';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'pagination' => false,
        ));
    }
}