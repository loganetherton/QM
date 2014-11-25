<?php
/**
 * Wrapper for PHPMailer.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
require_once dirname(__FILE__).'/phpmailer/class.phpmailer.php';

class Mailer extends CApplicationComponent
{
    /**
     * @var string $dryRun Whether to disable actually sending mail.
     */
    public $dryRun = false;

    /**
     * @var string $pathViews The path to the directory where the views for getView are stored.
     */
    public $pathViews = 'application.views.email';

    /**
     * @var string $pathLayouts The path to the directory where the layouts for getView are stored.
     */
    public $pathLayouts = 'application.views.layouts';

    /**
     * @var string $defaultLayout Default email template
     */
    public $layout;

    /**
     * @var object $mailer PHPMailer instance.
     */
    private $mailer;

    /**
     * Constructor. Initiate and setup PHPMailer class.
     */
    public function __construct()
    {
        $this->mailer = new PHPMailer;
        $this->mailer->CharSet = 'UTF-8';
    }

    /**
     * Call a PHPMailer function.
     *
     * @param string $method the method to call
     * @param array $params the parameters
     * @return mixed
     */
    public function __call($method, $params)
    {
        try {
            return parent::__call($method, $params);
        }
        catch(CException $e) {
            if(is_object($this->mailer) && get_class($this->mailer) === 'PHPMailer') {
                return call_user_func_array(array($this->mailer, $method), $params);
            }
            else {
                throw $e;
            }
        }
    }

    /**
     * Setter.
     *
     * @param string $name the property name
     * @param string $value the property value
     */
    public function __set($name, $value)
    {
        try {
            return parent::__set($name, $value);
        }
        catch(CException $e) {
            if(is_object($this->mailer) && get_class($this->mailer) === 'PHPMailer') {
                $this->mailer->$name = $value;
            }
            else {
                throw $e;
            }
        }
    }

    /**
     * Getter.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        }
        catch(CException $e) {
            if(is_object($this->mailer) && get_class($this->mailer) === 'PHPMailer') {
                return $this->mailer->$name;
            }
            else {
                throw $e;
            }
        }
    }

    /**
     * Set message subject.
     * @param string $subject Message subject
     */
    public function SetSubject($subject)
    {
        $this->mailer->Subject = $subject;
    }

    /**
     * Send email.
     */
    public function Send()
    {
        if($this->dryRun === true) {
            return true;
        }

        return $this->mailer->Send();
    }

    /**
     * Use views to generate message body.
     *
     * @param string $view email body view
     * @param array $vars email body variables
     * @param string $layout email layout
     */
    public function getView($view, $vars = array(), $layout = null)
    {
        if($layout === null) {
            $layout = $this->layout;
        }

        $body = Yii::app()->controller->renderPartial($this->pathViews.'.'.$view, $vars, true);

        if($layout !== null) {
            $body = Yii::app()->controller->renderPartial($this->pathLayouts.'.'.$layout, array('content'=>$body), true);
        }

        $this->mailer->MsgHTML($body);
    }
}