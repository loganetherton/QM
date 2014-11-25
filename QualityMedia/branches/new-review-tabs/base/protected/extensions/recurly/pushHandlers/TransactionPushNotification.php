<?php
/**
 * Transaction-related push notifications.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class TransactionPushNotification extends BasePushNotificationHandler
{
    /**
     * Class constructor.
     * @param object $notification Notification object
     * @param string $modelName Related model name
     */
    public function __construct($notification, $modelName)
    {
        parent::__construct($notification, $modelName);

        $this->transaction = Recurly_Transaction::get($notification->transaction->id);
        $this->invoice = $this->transaction->invoice->get();

        // Transaction may not belong to subscription
        if($this->transaction->subscription !== null) {
            $this->subscription = $this->transaction->subscription->get();
        }
    }

    /**
     * Handle notification.
     */
    public function handle()
    {
        $event = new CEvent($this);

        // All events has to be called because push notifications order is not reliable
        // This means that new transaction notification may come before new subscription event
        $this->onAccountPushNotification($event);
        $this->onSubscriptionPushNotification($event);
        $this->onTransactionPushNotification($event);
        $this->onInvoicePushNotification($event);
    }
}