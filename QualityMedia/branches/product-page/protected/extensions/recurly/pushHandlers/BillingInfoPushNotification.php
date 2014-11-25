<?php
/**
 * BillingInfo-related push notifications.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class BillingInfoPushNotification extends BasePushNotificationHandler
{
    /**
     * Handle notification.
     */
    public function handle()
    {
        $event = new CEvent($this);

        // onAccountPushNotification has to be called first because push notifications order is not reliable.
        // This means that billing info notification may come before new account event
        $this->onAccountPushNotification($event);
        $this->onBillingInfoPushNotification($event);
    }
}