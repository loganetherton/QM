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

            if (!is_array(Yii::app()->params['contactEmail'])) {
                $mail->AddAddress(Yii::app()->params['contactEmail']);
            }
            else {
                foreach (Yii::app()->params['contactEmail'] as $address)
                    $mail->AddAddress($address);
            }

            if($type == 'contact') {
                $name = isset($_POST['name']) ? CHtml::encode($_POST['name']) : '';
                $businessName = isset($_POST['businessName']) ? CHtml::encode($_POST['businessName']) : '';
                $email = isset($_POST['email']) ? CHtml::encode($_POST['email']) : '';
                $message = isset($_POST['message']) ? CHtml::encode($_POST['message']) : '';
                $phone = isset($_POST['phone']) ? CHtml::encode($_POST['phone']) : '';

                $contactNames = array();

                if(!empty($name)) {
                    array_push($contactNames, $name);
                }

                if(!empty($businessName)) {
                    array_push($contactNames, $businessName);
                }

                $contactNames = implode(', ', $contactNames);

                $viewData = array('name'=>$contactNames, 'email'=>$email, 'message'=>$message, 'phone'=>$phone);

                $sources = array(
                    'lp1' => 'Yelp-Maintenance1',
                    'lp2' => 'Yelp-Maintenance2'
                );

                if(isset($_POST['source']) && in_array($_POST['source'], array_keys($sources))) {
                    $viewData['source'] = $sources[$_POST['source']];
                }
// var_dump($viewData); die;
                $mail->SetSubject(sprintf('QualityMedia contact from %s', $name));
                $mail->getView('contact', $viewData);
            }
            else {
                $name = CHtml::encode($_POST['name']);
                $phone = CHtml::encode($_POST['phone']);

                $mail->SetSubject('QualityMedia signup');
                $mail->getView('signup', array('name'=>$contactNames, 'phone'=>$phone));
            }

            $mail->Send();
        }
        else {
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again');
        }
    }
}