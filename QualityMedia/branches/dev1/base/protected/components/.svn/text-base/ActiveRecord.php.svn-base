<?php
/**
 * Active Record abstract class.
 * Custom modifications to yii's CActiveRecord class.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
abstract class ActiveRecord extends CActiveRecord
{
    /**
     * Prepare createdAt and updatedAt attributes before saving a record.
     * This method is invoked before saving a record (after validation, if any).
     * The default implementation raises the {@link onBeforeSave} event.
     * Use {@link isNewRecord} to determine whether the saving is
     * for inserting or updating record.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave()
    {
        if($this->getIsNewRecord()) {
            $this->createdAt = $this->updatedAt = date('Y-m-d H:i:s');
        }
        else {
            $this->updatedAt = date('Y-m-d H:i:s');
        }

        return parent::beforeSave();
    }

    /**
     * Returns an array with values for dropDown list
     * @param string $name Text to be displayed.
     * @return array Array with key => value pairs
     */
    public function dropDownList($name = 'name')
    {
        $criteria = new CDbCriteria;
        $criteria->select = array('id', $name);
        $criteria->order = $name;

        return CHtml::listData($this->findAll($criteria), 'id', $name);
    }
}