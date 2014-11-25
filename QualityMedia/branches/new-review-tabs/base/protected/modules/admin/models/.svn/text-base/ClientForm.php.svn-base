<?php
/**
 * Create client form.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class ClientForm extends CFormModel
{
    /**
     * @var object $client Client object.
     */
    public $client;

    /**
     * @var array $yelpBusinesses Yelp businesses.
     */
    public $yelpBusinesses = array();

    /**
     * Rewrite yelp businesses to local array.
     */
    public function getYelpBusinesses()
    {
        if($this->client->profile === null) {
            $this->yelpBusinesses = array();
        }
        else {
            $this->yelpBusinesses = $this->client->profile->yelpBusinesses;
        }
    }

    /**
     * Set campaign perks.
     * @param array $attributes Array of yelp businesses attributes (sent via POST)
     */
    public function setYelpBusinessAttributes($attributes)
    {
        foreach($this->yelpBusinesses as $i => $yelpBusiness) {
            $yelpBusiness->setAttributes($attributes[$i]);
        }
    }

    public function beforeSave()
    {
        //Force valid contract date format
        $this->client->billingInfo->contractDate = $this->convertToTimestamp($this->client->billingInfo->contractDate);

    }

    /**
     * Save model.
     * @return boolean Whether model has been saved
     */
    public function save()
    {
        $this->beforeSave();

        $transaction = Yii::app()->getDb()->beginTransaction();

        try {
            if($this->client->getIsNewRecord()) {
                $this->client->accountCode = $this->client->email;
            }

            if(!$this->client->save()) {
                throw new CException('Client has not been saved');
            }

            $this->client->billingInfo->userId = $this->client->id;
            if(!$this->client->billingInfo->save()) {
                throw new CException("Client's billing info has not been saved");
            }

            $this->client->profile->userId = $this->client->id;
            if(!$this->client->profile->save()) {
                throw new CException("Client's profile has not been saved");
            }

            $queueModel = new ProfileQueue;
            if(!$queueModel->addToQueue('fetchYelpBusinesses', array($this->client->profile->id))) {
                throw new CDbException("Account {$this->client->id} failed while adding to queue");
            }

            foreach($this->yelpBusinesses as $yelpBusiness) {
                if(!$yelpBusiness->save()) {
                    throw new CDbException("Yelp business {$yelpBusiness->bizId} has not been saved");
                }
            }

            // Update Client Account in Recurly if already has a subscrption
            if(!$this->client->getIsNewRecord() && $this->client->hasSubscription()) {

                Yii::app()->getComponent('recurly');

                try {
                    $recurlyAccount = new Recurly_Account($this->client->accountCode);
                    $recurlyAccount->email          = $this->client->email;
                    $recurlyAccount->first_name     = $this->client->billingInfo->firstName;
                    $recurlyAccount->last_name      = $this->client->billingInfo->lastName;
                    $recurlyAccount->company_name   = CHtml::encode($this->client->billingInfo->companyName);

                    $billingInfo = new Recurly_BillingInfo();

                    $billingInfo->address1 = $this->client->billingInfo->address1;
                    $billingInfo->city     = $this->client->billingInfo->city;
                    $billingInfo->state    = $this->client->billingInfo->state;
                    $billingInfo->zip      = $this->client->billingInfo->zipCode;
                    $billingInfo->phone    = $this->client->billingInfo->phone;
                    $recurlyAccount->billing_info = $billingInfo;

                    $recurlyAccount->update();
                }
                catch(Recurly_Error $e) {
                    Yii::log('Muted Recurly Error: ' . $e->getMessage(), CLogger::LEVEL_ERROR);
                }
            }


            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            $transaction->rollback();

            return false;
        }
    }

    /**
     * Converts the input value to a timestamp format
     * @param  string $value custom date
     * @return
     */
    public function convertToTimestamp($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * Converts the input value to a date picker format
     * @param  string $value custom date
     * @return
     */
    public function convertToDatePickerFormat($value)
    {
        return date('m/d/Y', strtotime($value));
    }
}