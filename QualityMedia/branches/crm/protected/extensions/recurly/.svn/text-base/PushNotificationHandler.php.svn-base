<?php
/**
 * Push notification handler.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 * @see http://docs.recurly.com/api/push-notifications
 */
Yii::import('recurly.pushHandlers.*');

class PushNotificationHandler extends CFormModel
{
    /**
     * @var object $notification SimpleXMLElement with notification details.
     */
    protected $notification;

    /**
     * Initializes this model.
     */
    public function init()
    {
        $this->parseNotificationXml();
    }

    /**
     * Parse notification XML.
     */
    protected function parseNotificationXml()
    {
        $xml = file_get_contents('php://input');

        $this->notification = new Recurly_PushNotification($xml);
    }

    /**
     * Handle notification.
     * @param object $recurlyApi RecurlyApi instance
     */
    public function handle(RecurlyApi $recurlyApi)
    {
        switch($this->notification->type) {
            case 'new_account_notification':
            case 'canceled_account_notification':
                // Account related notifications
                $handler = new AccountPushNotification($this->notification, $recurlyApi);
                break;
            case 'billing_info_updated_notification':
                // Billing info related notification
                $handler = new BillingInfoPushNotification($this->notification, $recurlyApi);
                break;
            case 'new_subscription_notification':
            case 'updated_subscription_notification':
            case 'canceled_subscription_notification':
                // Subscription reactivation after canceled
            case 'reactivated_account_notification':
            case 'expired_subscription_notification':
            case 'renewed_subscription_notification':
                // Subscription related notifications
                $handler = new SubscriptionPushNotification($this->notification, $recurlyApi);
                break;
            case 'successful_payment_notification':
            case 'failed_payment_notification':
            case 'successful_refund_notification':
            case 'void_payment_notification':
                // Transaction (payments) related notifications
                $handler = new TransactionPushNotification($this->notification, $recurlyApi);
                break;
            default:
                throw new CException('Unknown notification type: '.$this->notification->type);
                break;
        }

        $handler->handle();
    }
}