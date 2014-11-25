<?php
/**
 * Subscription-related push notifications.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class SubscriptionPushNotification extends BasePushNotificationHandler
{
    /**
     * Class constructor.
     * @param object $notification Notification object
     * @param string $modelName Related model name
     */
    public function __construct($notification, $modelName)
    {
        parent::__construct($notification, $modelName);

        $this->subscription = Recurly_Subscription::get($notification->subscription->uuid);
    }

    /**
     * Handle notification.
     */
    public function handle()
    {
        $event = new CEvent($this);

        // Both events has to be called because push notifications order is not reliable
        // This means that new subscription notification may come before new account event
        $this->onAccountPushNotification($event);
        $this->onSubscriptionPushNotification($event);
    }
}