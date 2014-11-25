<?php
/**
 * Client summary model for admin module.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class AdminClientSummary extends User
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array List of users with no subscription assigned.
     */
    public function getUsersWithNoSubscription()
    {
        $criteria = new CDbCriteria;
        $criteria->with = array('subscriptions');
        $criteria->together = true;
        // $criteria->join='LEFT JOIN Subscriptions ON t.id=Subscriptions.userId AND Subscriptions.state != "Active"';

        $criteria->select = array('t.*', 'SUM((IF(subscriptions.state = "active", 1, 0))) AS activeSubscriptions');
        $criteria->having = 'activeSubscriptions < 1';
        $criteria->group = 't.id';

        $this->getDbCriteria()->mergeWith($criteria);

        $dataProvider =  new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));

        $dataProvider->setTotalItemCount(count($this->findAll($criteria)));

        return $dataProvider;
    }

    /**
     * @return array List of users with no salesman assigned
     */
    public function getUsersWithNoSalesmanAssigned()
    {
        // Clear criteria first
        $this->setDbCriteria(new CDbCriteria);

        return $this->unassignedSalesman()->search();
    }

    /**
     * @return array List of users with no Account Manager assigned
     */
    public function getUsersWithNoAccountManagerAssigned()
    {
        // Clear criteria first
        $this->setDbCriteria(new CDbCriteria);

        return $this->unassignedAccountManager()->search();
    }

    /**
     * @return array List of all users with no social network credentials.
     */
    public function getUsersWithNoSocialNetworkAssigned()
    {
        // Clear criteria first
        $this->setDbCriteria(new CDbCriteria);

        return $this->noSocialNetworkAssigned()->search();
    }
}