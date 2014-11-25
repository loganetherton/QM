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
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return CMap::mergeArray(parent::rules(), array(
            array('companyName', 'safe', 'on'=>'search'),
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
            'updates',
            'privateMessages'=>array(
                'with'=>'review',
            ),
        );

        $criteria->compare('billingInfo.companyName', $this->companyName, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'lastActionAt DESC, t.id ASC',
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
            'privateMessages'=>array(
                'with'=>'review',
            ),
            'privateMessagesSearch'=>array(
                'select'=>false,
                'together'=>true,
            ),
        );

        $criteria->compare('billingInfo.companyName', $this->companyName, true);
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