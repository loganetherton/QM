<?php
/**
 * Helper model for salesman assignment.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SalesmanAssignment extends User
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array Validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('salesmanId', 'required'),
            array('salesmanId', 'exist', 'className'=>'Salesman', 'attributeName'=>'id', 'allowEmpty'=>false),
        );
    }
}