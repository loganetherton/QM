<?php
/**
 * Account Managers Stats for admin module.
 *
 * @author Dawid Majewski <dawid@qualitymedia.com>
 */
class AmStats extends AmActivity
{

    //Review Type Filter values
    const REVIEW_TYPE_FILTER_ALL = 0;
    const REVIEW_TYPE_FILTER_PRECONTRACT = 1;
    const REVIEW_TYPE_FILTER_POSTCONTRACT = 2;

    public $reviewTypeFilter = self::REVIEW_TYPE_FILTER_ALL;

    public $publicCommentCount;
    public $privateMessageCount;
    public $flagCount;

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array_merge(
                parent::relations(),
                array(
                'accountManager' => array(
                    self::BELONGS_TO,
                    'AccountManager', 'accountManagerId',
                ),
                'billingInfo' => array(
                    self::BELONGS_TO,
                    'BillingInfo', 'review.businessId',
                ),
            )
        );
    }

    public function scopes()
    {
        return array(
            'junior'  =>array(
                'condition' => 'accountManager.type = '.AccountManager::TYPE_JUNIOR,
            ),
            'precontract'  =>array(
                'condition' => 'review.precontract = '.Review::PRECONTRACT_TRUE,
            ),
            'fullPrecontract'  =>array(
                'condition' => 'billingInfo.contractDate > review.reviewDate',
            ),
            'postcontract'  =>array(
                'condition' => 'billingInfo.contractDate <= review.reviewDate',
            ),
        );
    }

    /**
     * Searching Function
     * @return CActiveDataProvider query results
     */
    public function search()
    {
        return $this->searchData();
    }

    /**
     * Returns the global stats for specified account manager serviced clients
     * @return CActiveDataProvider query results
     */
    public function getManagerServicedClients()
    {
        return $this->searchData($this->accountManagerId);
    }


    /**
     * Returns the global stats - helper function
     * @return CActiveDataProvider query results
     */
    protected function searchData($accountManagerId = null)
    {
        $criteria = $this->getDbCriteria();

        $criteria->select = array(
            "*",
            "SUM(IF(t.type = 'flag', 1, 0)) AS flagCount",
            "SUM(IF(t.type = 'publicComment', 1, 0)) AS publicCommentCount",
            "SUM(IF(t.type = 'privateMessage', 1, 0)) AS privateMessageCount"
        );

        $criteria->addCondition(sprintf('t.status IN(%s,%s)', Review::APPROVAL_STATUS_ACCEPTED, Review::APPROVAL_STATUS_CHANGED));

        if(!empty($this->dateRange)) {
            $dateRange = explode(' - ', $this->dateRange);
            $criteria->addBetweenCondition('t.createdAt', date('Y-m-d', strtotime($dateRange[0])), date('Y-m-d', strtotime($dateRange[1])));
        }

        //Group by Account Managers, if the accountManagerid is specified, group by client
        if($accountManagerId) {
            $criteria->group = 'billingInfo.companyName';
            $criteria->addCondition("t.accountManagerId = {$accountManagerId}");
        }
        else {
            $criteria->group = 'billingInfo.companyName';
        }
        $criteria->group = ($accountManagerId ? 'billingInfo.companyName': 'accountManager.id');

        $criteria->with = array(
            'accountManager' => array(
                'together' => true,
            ),
            'review' => array(
                'select' => 'id',
                'together' => true,
            ),
            'review.user' => array(
                'select' => 'id',
            ),
            'review.user.billingInfo'=>array(
                'select' => 'companyName',
                'together'=>true,
            ),
        );

        // $criteria->mergeWith($this->getDbCriteria());

        $result = new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=> array('class' => 'Pagination'),
            'sort'=>array(
                'defaultOrder'=> ($accountManagerId ? 'billingInfo.companyName ASC, t.id ASC': 'accountManager.lastName ASC, t.id ASC'),
                'attributes'=>array(
                    'flagCount'=>array(
                        'asc'=>'flagCount ASC, accountManager.lastName ASC',
                        'desc'=>'flagCount DESC, accountManager.lastName ASC',
                    ),
                    'publicCommentCount'=> array(
                        'asc' => 'publicCommentCount ASC, accountManager.lastName ASC',
                        'desc' => 'publicCommentCount DESC, accountManager.lastName ASC',
                    ),
                    'privateMessageCount'=> array(
                        'asc' => 'privateMessageCount ASC, accountManager.lastName ASC',
                        'desc' => 'privateMessageCount DESC, accountManager.lastName ASC',
                    ),
                    'lastName'=> array(
                        'asc' => 'accountManager.lastName ASC',
                        'desc' => 'accountManager.lastName DESC',
                    ),
                    '*',
                )
            ),
        ));

        $result->setTotalItemCount(count($this->findAll($criteria)));

        return $result;
    }

    /**
     * Returns an array of available review filters
     * @return array array of keys and descriptions
     */
    public function getReviewFilterTypesList()
    {
        return array(
           self::REVIEW_TYPE_FILTER_ALL => 'All reviews',
           self::REVIEW_TYPE_FILTER_PRECONTRACT => 'Precontract reviews',
           self::REVIEW_TYPE_FILTER_POSTCONTRACT => 'Post contract reviews',
        );
    }
}