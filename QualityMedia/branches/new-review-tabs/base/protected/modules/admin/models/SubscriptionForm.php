<?php
/**
 * Subscription form.
 * This class is used to store a new subscription using recurly
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class SubscriptionForm extends CFormModel
{
    public $planCode;
    public $currency;
    public $accountCode;
    public $email;
    public $companyName;
    public $firstName;
    public $lastName;

    public $clientId;

    public $cardNumber;
    public $expirationDay;
    public $expirationMonth;
    public $securityCode;

    public $recurlyError = null;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('firstName, lastName, email, cardNumber, expirationDay, expirationMonth, securityCode,', 'required'),
            array('clientId, recurlyError', 'safe')
        );
    }

    /**
     * Save model.
     * @return boolean Whether model has been saved
     */
    public function save()
    {

        $subscriptionModel = new Subscription();
        $subscriptionExists = $subscriptionModel->find('planCode = :planCode AND userId = :userId AND state= :state', array('state'=>'canceled', 'planCode'=>$this->planCode, 'userId'=>$this->clientId));

        if($subscriptionExists !== null) {
            $this->reactivate($subscriptionExists->uuid);
            return true;
        }

        try {
            if(!$this->validate()) {
                throw new CException('Model has not been validated');
            }

            Yii::app()->getComponent('recurly');

            $subscription = new Recurly_Subscription;
            $subscription->plan_code    = $this->planCode;
            $subscription->currency     = $this->currency;

            $account = new Recurly_Account;
            $account->account_code  = $this->accountCode;
            $account->email         = $this->email;
            $account->company_name  = htmlentities($this->companyName);
            $account->first_name    = htmlentities($this->firstName);
            $account->last_name     = htmlentities($this->lastName);

            $billing_info = new Recurly_BillingInfo;
            $billing_info->number   = $this->cardNumber;
            $billing_info->year     = $this->expirationDay;
            $billing_info->month    = $this->expirationMonth;
            $billing_info->verification_value   = $this->securityCode;

            $account->billing_info = $billing_info;
            $subscription->account = $account;

            $subscription->create();

            return true;
        }
        catch(Recurly_ValidationError $e) {
            $replacements = array(
                'account code'                                        => 'email',
                'month is not a number'                               => 'invalid expiry date',
                'number is expired or has an invalid expiration date' => 'invalid expiry date',
                'year is not a number'                                => 'invalid expiry date',
                'year must be less than 2050'                         => 'invalid expiry date',
                'year must be greater than 2000'                      => 'invalid expiry date',
                'month must be less than 13'                          => 'invalid expiry date',
            );
            $messages = str_replace(array_keys($replacements), array_values($replacements), strtolower(trim($e->getMessage(), '.')));
            $messages = implode('. ', array_map('ucfirst', array_unique(explode(', ', $messages))));

            $this->addError('recurlyError', $messages.'.');
        }
        catch(Exception $e) {
        }

        return false;
    }

    /**
     * Reactivates the subscription in recurly
     * @param  string $uuid subscription identifier
     * @return void
     */
    public function reactivate($uuid)
    {
        Yii::app()->getComponent('recurly');
        $subscription = Recurly_Subscription::get($uuid);
        $subscription->reactivate();
    }
}