<?php
/**
 * Custom changes in abstract Controller class needed by Client module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ClientController extends Controller
{
    /**
     * @var string $layout The default layout for the controller view.
     */
    public $layout = '/layouts/main';
    
    
    public $preferencesUrl = array('user/preferences');
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'users'=>array('@'),
            ),
            array(
                'deny',
                'users'=>array('*'),
            ),
        );
    }
    
    public function beforeAction($action)
    {
        $controller = $action->getController();
        $id = $action->getId();
        if($controller instanceof SessionController){
            return TRUE;
        }
        if($controller instanceof UserController && $id === 'preferences'){
            return TRUE;
        }
        $id = Yii::app()->getUser()->getId();

        $userModel = $this->loadModel($id, 'User');
        
        if($userModel->password === $userModel->encryptPassword(Client::DEFAULT_PASSWORD, $userModel->salt)){
            Yii::app()->user->setFlash('warning', 'Please change your default password to continue!');
            $this->redirect($this->preferencesUrl);
        }
        return TRUE;
    }
}