<?php
/**
 * Create plan form.
 *
 * @author Dawid Majeski <dawid@qualitymedia.com>
 */
class PlanForm extends CFormModel
{
    /**
     * @var object $plan Plan object.
     */
    public $client;

    public $amount;
    public $setupFee;
    public $addons;

    public $startDate;

    public $businessName;
    public $firstName;
    public $lastName;
    public $email;
    public $phone;

    public $address1;
    public $address2;
    public $city;
    public $zip;
    public $state;
    public $country = 'US';

    public $cardNumber;
    public $securityCode;
    public $expirationMonth;
    public $expirationDay;

    public $salesmanId;
    public $clientId;

    public function rules()
    {
        return array(
            array('businessName, firstName, lastName, email, phone, salesmanId, clientId', 'safe'),
            array('address1, address2, city, zip, state, country', 'safe'),
            array('cardNumber, securityCode, expirationMonth, expirationDay, amount, setupFee, addons, startDate', 'safe'),
        );
    }

    /**
     * Saves input data creating user, plan and subscription
     * @return [type] [description]
     */
    public function save()
    {
        //load recurly library
        Yii::app()->getComponent('recurly');

        //Plan Saving
        $planModel = new Plan;
        $planCode = $this->createPlanCode($this->amount, $this->setupFee);

        if(!$planModel->findByPlanCode($planCode)) {
            $newPlan = $this->createPlan($this->amount, $this->setupFee);
            $this->saveRecurlyPlan($newPlan);
        }

        //Addons Checking
        $this->syncPlanAddons($planCode);

        //Subscription creating
        $subscription = new Recurly_Subscription();
        $subscription->plan_code = $planCode;
        $subscription->currency = 'USD';

        //add start date if provided
        if(!empty($this->startDate)) {
            $time = strtotime($this->startDate);
            $zone = date('T', $time);

            $startDate = date('Y-m-d H:i:s '.$zone, $time);
            $subscription->starts_at = $startDate;
        }

        $errors = array();

        if(empty($this->businessName)) {
            array_push($errors, 'Business Name is required');
        }

        if(empty($this->securityCode)) {
            array_push($errors, 'Verification value is required');
        }

        $userModel = new User;
        $user = $userModel->findByEmail($this->email);

        if(empty($this->clientId)) {

            if($user) {
                array_push($errors, 'Email has already been used');
            }

        }

        if(count($errors)) {
            $this->addError('recurlyError', implode(', ', $errors));
            return false;
        }
        else {
            $account = new Recurly_Account;
            $account->account_code  = $this->email;
            $account->email         = $this->email;
            $account->first_name    = htmlentities($this->firstName);
            $account->last_name     = htmlentities($this->lastName);
            $account->company_name  = htmlentities($this->businessName);
        }

        $billingInfo = new Recurly_BillingInfo;
        $billingInfo->first_name            = $this->firstName;
        $billingInfo->last_name             = $this->lastName;
        $billingInfo->number                = $this->cardNumber;
        $billingInfo->month                 = $this->expirationMonth;
        $billingInfo->year                  = $this->expirationDay;
        $billingInfo->verification_value    = $this->securityCode;
        $billingInfo->phone                 = $this->phone;
        $billingInfo->address1              = $this->address1;
        $billingInfo->address2              = $this->address2;
        $billingInfo->city                  = $this->city;
        $billingInfo->state                 = $this->state;
        $billingInfo->zip                   = $this->zip;
        $billingInfo->country               = $this->country;

        if(count((array) $this->addons)) {
            $subscriptionAddons = array();
            $systemAddons = $this->getAddons(true);

            foreach($this->addons as $addon) {

                $subscriptionAddon = new Recurly_SubscriptionAddOn();
                $subscriptionAddon->add_on_code = $addon;
                $subscriptionAddon->quantity = 1;
                $subscriptionAddon->unit_amount_in_cents = $systemAddons[$addon]['amount'] * 100;

                array_push($subscriptionAddons, $subscriptionAddon);
            }

            $subscription->subscription_add_ons = $subscriptionAddons;
        }


        $account->billing_info = $billingInfo;
        $subscription->account = $account;

        try {
            $subscription->create();

            //save client with assigned Salesman
            $userModel->setAttributes(array(
                'salesmanId'  => $this->salesmanId,
                'accountCode' => $this->email,
                'email'       => $this->email,
            ));

            $userModel->save();

            //If the subscription has a delayed status, save it now
            if(!empty($this->startDate)) {
                $subscriptionModel = new Subscription;
                $subscriptionModel->saveFromRecurlyObject($subscription, empty($this->clientId) ? $userModel->getPrimaryKey() : $this->clientId);
            }

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
            $messages = implode(', ', array_unique(explode(', ', $messages)));

            $this->addError('recurlyError', $messages);
        }
        catch(Exception $e) {
        }

        return false;
    }

    /**
     * Creates a subscription plan in recurly
     * @param  int $amount   Amount
     * @param  int $setupFee Setup Fee
     * @return Recurly Plan Object
     */
    public function createPlan($amount, $setupFee)
    {
        $planCode = $this->createPlanCode($amount, $setupFee);

        $plan = new Recurly_Plan();
        $plan->plan_code = $planCode;
        $plan->name = $this->createPlanName($amount, $setupFee);

        $plan->unit_amount_in_cents->addCurrency('USD', (float)$amount * 100);

        if($plan->setup_fee_in_cents) {
             $plan->setup_fee_in_cents->addCurrency('USD', (float)$setupFee * 100);
        }
        $plan->plan_interval_length = 1;
        $plan->plan_interval_unit = 'months';
        $plan->create();

        return Recurly_Plan::get($planCode);
    }

    /**
     * Returns a plan name generated from amounts from
     * @param  int $amount   Amount
     * @param  int $setupFee Setup Fee
     * @return string Plan Name
     */
    public function createPlanName($amount, $setupFee)
    {
        $feeTxt = 'waived';

        if($setupFee) {
            $feeTxt = '$'.$setupFee;
        }

        return '$'.$amount.'/month + '.$feeTxt.' one-time setup fee';
    }

    /**
     * Returns a plan code generated from amounts from
     * @param  int $amount   Amount
     * @param  int $setupFee Setup Fee
     * @return string Plan Code
     */
    public function createPlanCode($amount, $setupFee)
    {
        if(!$setupFee) {
            $setupFee = 'free';
        }

        return 'plan-'.str_replace('.', '_', $amount).'-'.str_replace('.', '_', $setupFee);
    }

    /**
     * Creates a plan from arecurly object
     * @param  $plan Recurly Plan Object
     * @return Saving result
     */
    public function saveRecurlyPlan($plan)
    {
        $amount = $plan->unit_amount_in_cents->getCurrency('USD');
        $setupFee = $plan->setup_fee_in_cents->getCurrency('USD');

        $attributes = array(
            'planCode' => $plan->plan_code,
            'name' => $plan->name,
            'amount' => $amount->amount_in_cents,
            'setupFee' => $setupFee->amount_in_cents,
            'intervalLength' => $plan->plan_interval_length,
            'intervalUnit' => $plan->plan_interval_unit,
        );

        $model = new Plan;
        $model->setAttributes($attributes);
        $model->save();
    }

    /**
     * Returns addons Array
     * @param  $flat boolean return flat list
     * @return [type] [description]
     */
    public function getAddons($flat = false)
    {
        $addons = array(
            'email-marketing' => array(
                'name'  => 'Email Marketing',
                'amount' => 100
            ),
            'socialstar'      => array(
                'name' => 'Social Star',
                'amount' => 100
            ),
            'tripadvisor'     => array(
                'name' => 'Trip Advisor',
                'amount' => 100
            ),
            'foursquare'     => array(
                'name' => 'Foursquare',
                'amount' => 100
            )
        );

        $options = array(
            'email-marketing' => array(
                'name'  => 'Email Marketing',
                'amount' => array(
                    'email-marketing-0' => array(
                        'name'  => 'Email Marketing',
                        'optionName'  => 'Up to 1,000 emails',
                        'amount' => 40
                    ),
                    'email-marketing-1' => array(
                        'name'  => 'Email Marketing',
                        'optionName'  => '1,000 - 2,000 emails',
                        'amount' => 75
                    ),
                    'email-marketing-2' => array(
                        'name'  => 'Email Marketing',
                        'optionName'  => '2,000 + emails',
                        'amount' => 100
                    )
                )
            ),
        );

        if($flat) {

            foreach($options as $optionGroup => $option) {
                unset($addons[$optionGroup], $optionGroup);
                $addons = array_merge($addons, $option['amount']);
            }

        }
        else {
            $addons = array_merge($addons, $options);
        }

        return $addons;
    }

    /**
     * Synchronize plan Addons with existing system addons list
     * @param  [type] $planCode [description]
     * @return [type]           [description]
     */
    public function syncPlanAddons($planCode)
    {
        $systemAddons = $this->getAddons(true);

        $planAddons = array();

        foreach (Recurly_AddonList::get($planCode) as $addon) {
            array_push($planAddons, $addon->add_on_code);

        }

        $syncList = array_diff(array_keys($systemAddons), $planAddons);

        if(!count($syncList)) {
            return;
        }

        foreach($syncList as $addon) {

            $recurlyAddon = new Recurly_Addon();
            $recurlyAddon->plan_code = $planCode;
            $recurlyAddon->add_on_code = $addon;
            $recurlyAddon->name = $systemAddons[$addon]['name'];
            $recurlyAddon->unit_amount_in_cents->addCurrency('USD', $systemAddons[$addon]['amount'] * 100);
            $recurlyAddon->create();

        }
    }
}