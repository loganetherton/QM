<?php
/**
 * Abstract push notification handler.
 * This class is base for all push notification handlers.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
abstract class BasePushNotificationHandler extends CComponent
{
    /**
     * @var object $account SimpleXMLElement Account object.
     */
    public $account;

    /**
     * @var object $billingInfo SimpleXMLElement BillingInfo object.
     */
    public $billingInfo;

    /**
     * @var object $subscription SimpleXMLElement Subscription object.
     */
    public $subscription;

    /**
     * @var object $transaction SimpleXMLElement Transaction object.
     */
    public $transaction;

    /**
     * @var object $invoice SimpleXMLElement Invoice object.
     */
    public $invoice;

    /**
     * @var object $notification SimpleXMLElement Notification object.
     */
    protected $notification;

    /**
     * Class constructor.
     * @param object $notification Notification object
     * @param string $modelName Related model name
     */
    public function __construct($notification, $recurlyApi)
    {
        $this->notification = $notification;
        $this->account = Recurly_Account::get($notification->account->account_code);

        try {
            // Billing info is empty for canceled subscriptions
            $this->billingInfo = Recurly_BillingInfo::get($notification->account->account_code);
        }
        catch(Exception $e) {
            // Do nothing
        }

        // Attach events
        $this->attachEvent('onAccountPushNotification',$recurlyApi->accountModel);
        $this->attachEvent('onBillingInfoPushNotification',$recurlyApi->billingInfoModel);
        $this->attachEvent('onSubscriptionPushNotification',$recurlyApi->subscriptionModel);
        $this->attachEvent('onTransactionPushNotification',$recurlyApi->transactionModel);
        $this->attachEvent('onInvoicePushNotification',$recurlyApi->invoiceModel);
    }

    /**
     * Attach event.
     * @param string $eventName Event name
     * @param string $modelName Related model name
     */
    public function attachEvent($eventName, $modelName)
    {
        // First check if class and method exist to avoid errors
        if(class_exists($modelName) && method_exists($modelName, 'handlePushNotification')) {
            $this->attachEventHandler($eventName,array(new $modelName, 'handlePushNotification'));
        }
    }

    /**
     * Raise onAccountPushNotification event.
     * @param object $event The event parameter
     */
    public function onAccountPushNotification($event)
    {
        $this->raiseEvent('onAccountPushNotification', $event);
    }

    /**
     * Raise onBillingInfoPushNotification event.
     * @param object $event The event parameter
     */
    public function onBillingInfoPushNotification($event)
    {
        $this->raiseEvent('onBillingInfoPushNotification', $event);
    }

    /**
     * Raise onSubscriptionPushNotification event.
     * @param object $event The event parameter
     */
    public function onSubscriptionPushNotification($event)
    {
        $this->raiseEvent('onSubscriptionPushNotification', $event);
    }

    /**
     * Raise onTransactionPushNotification event.
     * @param object $event The event parameter
     */
    public function onTransactionPushNotification($event)
    {
        $this->raiseEvent('onTransactionPushNotification', $event);
    }

    /**
     * Raise onInvoicePushNotification event.
     * @param object $event The event parameter
     */
    public function onInvoicePushNotification($event)
    {
        $this->raiseEvent('onInvoicePushNotification', $event);
    }

    /**
     * Handle notification.
     */
    abstract protected function handle();
}