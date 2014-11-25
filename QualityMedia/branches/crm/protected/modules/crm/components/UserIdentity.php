<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *
 * @author Logan Etherton <logan@qualitymedia.com>
 * @author Shitiz Garg <shitiz@qualitymedia.com>
 */
class UserIdentity extends CUserIdentity
{
    const ROLE_SALESMAN = 'salesman';
    const ROLE_AM = 'accountManager';
    const ROLE_ADMIN = 'admin';

    public static $roles = array(
        self::ROLE_SALESMAN => array(
            'name' => 'Salesman',
            'class' => 'Salesman',
        ),
        self::ROLE_AM => array(
            'name' => 'Account Manager',
            'class' => 'AccountManager',
        ),
        self::ROLE_ADMIN => array(
            'name' => 'Administrator',
            'class' => 'Admin',
        ),
    );

    public $id;
    public $userRoles;
    public $limitRole;

    /**
     * Constructor
     *
     * @access public
     * @param string $username
     * @param string $password
     * @param string $limitRole Whether to limit the current user to a role or not
     */
    public function __construct($username, $password, $limitRole = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->limitRole = $limitRole;
    }

    /**
     * Authenticates a user based on username and password.
     * This method is required by IUserIdentity.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        foreach (self::$roles as $role => $data) {
            $model = $data['class']::model()->findByUsername($this->username);

            if ($model == null
                || $model->encryptPassword($this->password, $model->salt) !== $model->password) {
                continue;
            }
            if (!is_null($this->limitRole) && $this->limitRole != $role) {
                continue;
            }
            
            // Set the individual roles for this user
            $this->userRoles[$role] = array(
                'data' => $data,
                'model' => $model,
                'id' => $model->id,
                'name' => $role,
            );
        }
        
        $this->assignRoles();

        return $this->errorCode;
    }
    
    public function assignRoles()
    {
        if (empty($this->userRoles)) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            $this->errorCode = self::ERROR_NONE;
            $this->id = $this->getId();

            // Make sure the current user is properly assigned all the roles in Auth Manager
            $auth = Yii::app()->authManager;
            foreach ($this->userRoles as $role => $info) {
                if (!$auth->isAssigned($role, $this->id)) {
                    $auth->assign($role, $this->id);
                }
            }
        }
        return $this;
    }

    /**
     * Returns all this user's roles
     *
     * @access public
     * @return array
     */
    public function getRoles()
    {
        return $this->userRoles;
    }

    /**
     * Returns the role based ID for this user (salesman ID, account manager ID etc.)
     *
     * @access public
     * @param string $role
     * @return int
     */
    public function getRoleId($role)
    {
        return $this->userRoles[$role]['id'];
    }

    /**
     * Returns the unique identifier for the identity.
     *
     * @access public
     * @return string the unique identifier for the identity
     * @return string A concatenated unique representation of all the current roles
     */
    public function getId()
    {
        $id_parts = array();
        foreach ($this->userRoles as $role) {
            $id_parts[] = $role['name'] . '_' . $role['id'];
        }
        return implode('_', $id_parts);
    }
}