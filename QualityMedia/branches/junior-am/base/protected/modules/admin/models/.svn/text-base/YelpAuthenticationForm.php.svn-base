<?php
/**
 * Yelp authentication form.
 * It also returns list of available businesses for the account.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class YelpAuthenticationForm extends CFormModel
{
    /**
     * @var string $username Yelp account username.
     */
    public $username;

    /**
     * @var string $password Yelp account password.
     */
    public $password;

    /**
     * @var string $yelpError Yelp error.
     */
    public $yelpError;

    /**
     * @var boolean $isValid Whether account login is valid.
     */
    public $valid;

    /**
     * @var array $businesses Available businesses.
     */
    public $businesses;

    /**
     * @return array Validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('username, password', 'required'),
        );
    }
    /**
     * Authenticate account.
     * @return boolean Whether account is valid
     */
    public function authenticate()
    {
        if(!$this->validate()) {
            return false;
        }

        $phantom = Yii::app()->getComponent('phantomjs');

        $phantom->setParams(array(
            $this->username,
            $this->password,
        ));

        $result = CJSON::decode($phantom->execute('yelp_authenticate_login.js'));

        if(isset($result['error'])) {
            $this->addError('yelpError', $result['error']);

            return false;
        }

        $this->valid = true;
        $this->businesses = $result['biz_list'];

        return true;
    }
}