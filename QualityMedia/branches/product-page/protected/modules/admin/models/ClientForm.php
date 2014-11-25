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
     * Save model.
     * @return boolean Whether model has been saved
     */
    public function save()
    {
        $transaction = Yii::app()->getDb()->beginTransaction();

        try {
            $isNewRecord = $this->client->getIsNewRecord();

            if($isNewRecord) {
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

            // Create an account in recurly
            Yii::app()->getComponent('recurly');

            $recurlyAccount = new Recurly_Account($this->client->accountCode);
            $recurlyAccount->email          = $this->client->email;
            $recurlyAccount->first_name     = $this->client->billingInfo->firstName;
            $recurlyAccount->last_name      = $this->client->billingInfo->lastName;
            $recurlyAccount->company_name   = CHtml::encode($this->client->billingInfo->companyName);

            $isNewRecord ? $recurlyAccount->create() : $recurlyAccount->update();

            $transaction->commit();

            return true;
        }
        catch(Exception $e) {
            $transaction->rollback();

            return false;
        }
    }
}