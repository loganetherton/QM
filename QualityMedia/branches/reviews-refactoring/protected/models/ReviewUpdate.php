<?php
/**
 * This is the model class for table "review_updates".
 *
 * The followings are the available columns in table 'review_updates':
 * @property integer $id
 * @property integer $reviewId
 * @property string $updateId
 * @property string $updateContent
 * @property string $updateHash
 * @property integer $starRating
 * @property string $updateDate
 * @property string $createdAt
 * @property string $updatedAt
 *
 * The followings are the available model relations:
 * @property Review $review
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ReviewUpdate extends ActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className Active record class name.
     * @return ReviewUpdate The static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string The associated database table name
     */
    public function tableName()
    {
        return 'review_updates';
    }

    /**
     * @return array Validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('reviewId, updateId, updateContent, updateHash, starRating, updateDate', 'required'),
            array('reviewId', 'exist', 'className'=>'Review', 'attributeName'=>'id'),
            array('starRating', 'numerical', 'integerOnly'=>true),
        );
    }

    /**
     * @return array Relational rules.
     */
    public function relations()
    {
        return array(
            'review' => array(self::BELONGS_TO, 'Review', 'reviewId'),
        );
    }

    /**
     * @return array Customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'reviewId' => 'Review',
            'updateId' => 'Update',
            'updateContent' => 'Update Content',
            'updateHash' => 'Update Hash',
            'starRating' => 'Star Rating',
            'updateDate' => 'Update Date',
            'createdAt' => 'Created At',
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
        $this->updateHash = sha1($this->updateId.$this->updateContent);

        return parent::beforeSave();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider The data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}