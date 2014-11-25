<?php
/**
 * Contact controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ContactController extends Controller
{
    /**
     * @var string $defaultAction The name of the default action.
     */
    public $defaultAction = 'create';

    /**
     * Create action.
     */
    public function actionCreate()
    {
        if(Yii::app()->getRequest()->getIsPostRequest()) {
            $type = isset($_POST['ref']) ? $_POST['ref'] : null;

            $mail = Yii::app()->getComponent('mailer');
            $mail->ClearAllRecipients();
            $mail->AddAddress(Yii::app()->params['contactEmail']);

            if($type == 'contact') {
                $name = CHtml::encode($_POST['name']);
                $email = CHtml::encode($_POST['email']);
                $message = CHtml::encode($_POST['message']);

                $mail->SetSubject(sprintf('QualityMedia contact from %s', $name));
                $mail->getView('contact', array('name'=>$name, 'email'=>$email, 'message'=>$message));
            }
            else {
                $name = CHtml::encode($_POST['name']);
                $phone = CHtml::encode($_POST['phone']);

                $mail->SetSubject('QualityMedia signup');
                $mail->getView('signup', array('name'=>$name, 'phone'=>$phone));
            }

            $mail->Send();
        }
        else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again');
        }
    }
}