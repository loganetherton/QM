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

    public $businessName;
    public $firstName;
    public $lastName;
    public $email;

    public $cardNumber;
    public $securityCode;
    public $expirationMonth;
    public $expirationDay;

    public $salesmanId;


    public function rules()
    {
        return array(
            array('businessName, firstName, lastName, email, cardNumber, securityCode, expirationMonth, expirationDay, salesmanId, amount, setupFee', 'safe'),
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

        //Subscription creating
        $subscription = new Recurly_Subscription();
        $subscription->plan_code = $planCode;
        $subscription->currency = 'USD';

        $errors = array();

        if(empty($this->businessName)) {
            array_push($errors, 'Business Name is required');
        }

        if(empty($this->securityCode)) {
            array_push($errors, 'Verification value is required');
        }

        $userModel = new User;
        $user = $userModel->findByEmail($this->email);
        if($user) {
            array_push($errors, 'Email has already been used');
        }

        if(count($errors)) {
            $this->addError('recurlyError', implode(', ', $errors));
            return false;
        }
        else {
            $account = new Recurly_Account();
            $account->account_code  = $this->email;
            $account->email = $this->email;
            $account->first_name = $this->firstName;
            $account->last_name = $this->lastName;
            $account->company_name = $this->businessName;
        }

        $billingInfo = new Recurly_BillingInfo();
        $billingInfo->first_name = $this->firstName;
        $billingInfo->last_name = $this->lastName;
        $billingInfo->number = $this->cardNumber;
        $billingInfo->month = $this->expirationMonth;
        $billingInfo->year = $this->expirationDay;
        $billingInfo->verification_value = $this->securityCode;

        $account->billing_info = $billingInfo;
        $subscription->account = $account;

        try {
            $subscription->create();

            //save client with assigned Salesman
            $userModel->setAttributes(array(
                'salesmanId' => $this->salesmanId,
                'accountCode' => $this->email,
                'email' => $this->email,
            ));

            $userModel->save();
            return true;
        }
        catch(Recurly_ValidationError $e) {
            $replacements = array(
                'account code' => 'email',
                'month is not a number' => 'invalid expiry date',
                'number is expired or has an invalid expiration date' => 'invalid expiry date',
                'year is not a number' => 'invalid expiry date',
                'year must be less than 2050' => 'invalid expiry date',
                'year must be greater than 2000' => 'invalid expiry date',
                'month must be less than 13' => 'invalid expiry date',
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
}