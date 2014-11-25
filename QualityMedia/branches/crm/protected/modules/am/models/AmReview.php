<?php
/**
 * Wrapper for Review model for Account Manager module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AmReview extends Review
{
    /**
     * @var string $companyName Company name search attribute.
     */
    public $companyName;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return YelpReview the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Returns the relations, extends them to include note
     *
     * @access public
     * @return array
     */
    public function relations()
    {
        return array_merge(parent::relations(), array(
            'notes' => array(self::HAS_MANY, 'Note', 'reviewId'),
        ));
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return CMap::mergeArray(parent::rules(), array(
            array('companyName, id', 'safe', 'on'=>'search'),
        ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array(
            'user'=>array(
                'select'=>'id',
                'together'=>true,
            ),
            'user.billingInfo'=>array(
                'select'=>'companyName',
                'together'=>true,
            ),
            'yelpBusiness',
            'updates',
            'privateMessages'=>array(
                'with'=>'review',
            ),
            'notes' => array(
                'scopes' => array('notArchived', 'isReviewNote'),
                'with' => array(
                    'accountManager' => array(
                        'together' => true,
                    ),
                ),
                'order' => 'important DESC',
            ),
        );

        $criteria->compare('user.status', 1);
        $criteria->compare('yelpBusiness.label', $this->companyName, true);
        $criteria->compare('yelpBusiness.status', 1);
        $criteria->compare('t.id', $this->id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'lastActionAt DESC, t.id ASC',
                'attributes'=>array(
                    'companyName'=>array(
                        'asc'=>'companyName ASC',
                        'desc'=>'companyName DESC',
                    ),
                    '*',
                )
            ),
        ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function searchMessages()
    {
        $criteria = new CDbCriteria;

        $criteria->with = array(
            'user',
            'user.billingInfo'=>array(
                'select'=>'companyName',
                'together'=>true,
            ),
            'yelpBusiness',
            'privateMessages'=>array(
                'with'=>'review',
            ),
            'privateMessagesSearch'=>array(
                'select'=>false,
                'together'=>true,
            ),
        );

        $criteria->compare('user.status', 1);
        $criteria->compare('yelpBusiness.label', $this->companyName, true);
        $criteria->compare('yelpBusiness.status', 1);
        $criteria->addNotInCondition('privateMessagesSearch.messageContent', array(null));

        $criteria->group = 't.id';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'t.latestMessageDate DESC, t.id ASC',
            ),
        ));
    }
}