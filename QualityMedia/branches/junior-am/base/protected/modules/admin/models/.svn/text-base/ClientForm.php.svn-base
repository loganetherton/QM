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

    /**
     * Save model.
     * @return boolean Whether model has been saved
     */
    public function save()
    {
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

            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            $transaction->rollback();

            return false;
        }
    }
}