<?php
/**
 * This is the model class for table "admins".
 *
 * The followings are the available columns in table 'admins':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $firstName
 * @property string $lastName
 * @property string $fullName
 * @property string $last_visit
 * @property string $created_at
 * @property string $updated_at
 *
 * @author Nitesh Pandey <nitesh@qualitymedia.com>
 */
class Client extends User
{
    /**
     * Returns the static model of the specified AR class.
     * @return Admin the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string User full name
     */
    public function getFullName($glue = ' ')
    {
        $fullName = $this->fullName;

        if(empty($fullName)) {
            $fullName = $this->billingInfo->lastName.$glue.$this->billingInfo->firstName;
        }
        return $fullName;;
    }

}