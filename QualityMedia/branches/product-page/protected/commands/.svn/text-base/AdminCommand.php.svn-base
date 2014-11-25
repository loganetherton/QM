<?php
/**
 * Admin account command tool.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AdminCommand extends CConsoleCommand
{
    /**
     * @var integer $passwordLength Password length.
     */
    public $passwordLength = 8;

    /**
     * Displays a usage error.
     * This method will then terminate the execution of the current application.
     * @param string $message the error message
     */
    public function usageError($message)
    {
        echo "Error: $message\n\n";
        exit(1);
    }

    /**
     * Create new admin account.
     */
    public function actionCreate($args)
    {
        if(!isset($args[0])) {
            $this->usageError('You have to provide admin username.');
        }

        $passwordLength = (int)$this->passwordLength;

        if($passwordLength <= 5) {
            $this->usageError('Wrong length password (minimum is 5).');
        }

        Yii::import('application.modules.admin.models.Admin');

        $model = new Admin;

        $username = trim($args[0]);

        $criteria = new CDbCriteria;
        $criteria->compare('username',$username);

        if($model->exists($criteria)) {
            $this->usageError("'{$username}' is already taken.");
        }

        if(!$this->confirm("Are you sure you want to create a new account for {$username}?")) {
            return;
        }

        // Get password from params or use random
        $password = isset($args[1]) ? $args[1] : Text::random('alnum', $passwordLength);

        $model->username    = $username;
        $model->salt        = $model->encryptPassword(Text::random('alnum', 40), Text::random('alnum', 40));
        $model->password    = $model->encryptPassword($password, $model->salt);

        if($model->save()) {
            echo "New admin account has been created [{$username} / {$password}].\n\n";
            return;
        }
var_dump($model->getErrors()); die;
        $this->usageError('Something went wrong. Please try again.');
    }
}