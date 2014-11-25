<?php
/**
 * Account-related push notifications.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AccountPushNotification extends BasePushNotificationHandler
{
    /**
     * Handle notification.
     */
    public function handle()
    {
        $event = new CEvent($this);

        $this->onAccountPushNotification($event);

        // Because of changes (or bug) in recurly API we need to check billingInfo push notifications together with account
        $this->onBillingInfoPushNotification($event);
    }
}