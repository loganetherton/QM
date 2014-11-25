<?php
/**
 * Recurly API wrapper.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
require_once dirname(__FILE__).'/lib/Recurly.php';

class RecurlyApi extends CComponent
{
    /**
     * @var string $apiKey Recurly API access key.
     */
    public $apiKey;

    /**
     * @var string Subdomain name.
     */
    public $subdomain;

    /**
     * @var string $privateKey Recurly private key (used for recurly.js).
     */
    public $privateKey;

    /**
     * @var string $planCode Recurly plan code to be used.
     */
    public $planCode;

    /**
     * @var string $currenct Currency code
     */
    public $currency;

    /**
     * @var string $accountModel Account related model.
     */
    public $accountModel;

    /**
     * @var string $billingInfoModel Billing info related model.
     */
    public $billingInfoModel;

    /**
     * @var string $subscriptionModel Subscription related model.
     */
    public $subscriptionModel;

    /**
     * @var string $transactionModel Transaction related model.
     */
    public $transactionModel;

    /**
     * @var string $invoiceModel Invoice related model.
     */
    public $invoiceModel;

    /**
     * Init component.
     */
    public function init()
    {
        if(empty($this->apiKey)) {
            throw new CException('The "apiKey" property cannot be empty.');
        }

        if(empty($this->subdomain)) {
            throw new CException('The "subdomain" property cannot be empty.');
        }

        Recurly_Client::$apiKey = $this->apiKey;
        Recurly_Client::$subdomain = $this->subdomain;

        // Set private key if not empty
        if(!empty($this->privateKey)) {
            Recurly_js::$privateKey = $this->privateKey;
        }

        $this->setRecurlyAlias();
    }

    /**
     * Register recurly path alias.
     */
    protected function setRecurlyAlias()
    {
        if(Yii::getPathOfAlias('recurly') === false) {
            Yii::setPathOfAlias('recurly', realpath(dirname(__FILE__)));
        }
    }

    /**
     * Handle push notification.
     */
    public function handlePushNotification()
    {
        // Import push notifications handler
        Yii::import('recurly.PushNotificationHandler');

        $handler = new PushNotificationHandler;
        $handler->handle($this);
    }

    /**
     * Cancel subscription.
     * @param string $uuid Subscription uuid
     */
    public function cancelSubscription($uuid)
    {
        $subscription = Recurly_Subscription::get($uuid);

        return $subscription->cancel();
    }

    /**
     * Get invoice PDF.
     * @param string $invoiceNumber Invoice number
     */
    public function getInvoicePdf($invoiceNumber)
    {
        try {
            return Recurly_Invoice::getInvoicePdf($invoiceNumber, 'en-US');
        }
        catch(Recurly_NotFoundError $e) {
            throw new CException('Invoice not found');
        }
    }
}