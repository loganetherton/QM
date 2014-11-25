<?php
/**
 * LoginForm class.
 * LoginForm is the data structure for keeping user login form data.
 * It is used by the 'create' action of 'SessionController'.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class JuniorAmActivity extends AccountManager
{

    public $dateRange;
    public $jrId;
    public $clientId;

    public function searchActions()
    {

        //Set filters
        $filters = array();
        $queryFilters = '';
        $having = '';

        if(!empty($this->clientId) && is_numeric($this->clientId)) {
            array_push($filters, sprintf("(b.userId = %s)", (int)$this->clientId));
        };

        if(!empty($this->dateRange) && count(explode(' - ', $this->dateRange)) == 2) {
            $dateRange = explode(' - ', $this->dateRange);
            $dateRange[0] = date('Y-m-d', strtotime($dateRange[0]));
            $dateRange[1] = date('Y-m-d', strtotime($dateRange[1]));
            array_push($filters, sprintf("(actionDate >= '%s' AND actionDate <= '%s')", $dateRange[0], $dateRange[1]));
        };

        if(count($filters)) {
            $queryFilters = ' AND '.implode(" AND ", $filters);
        }

        //Sql Union
        $sql['publicComments']  = "SELECT pc.id, b.userId as userId, b.companyName as companyName, 'publicComment' AS actionType, publicCommentDate AS actionDate
            FROM `reviews` pc
            LEFT JOIN `billing_info` b ON b.userId = pc.businessId
            WHERE pc.publicCommentAuthor != '' AND pc.accountManagerId = ".$this->id.str_replace('actionDate', 'publicCommentDate', $queryFilters);
        $sql['flags']  = "SELECT f.id, b.userId as userId, b.companyName as companyName, 'flag' AS actionType, flaggedAt AS actionDate
            FROM  `reviews` f
            LEFT JOIN billing_info b ON b.userId = f.businessId
            WHERE f.status = ".Review::STATUS_FLAGGED." AND f.accountManagerId = ".$this->id.str_replace('actionDate', 'flaggedAt', $queryFilters);
        $sql['messages']  = "SELECT m.id, b.userId as userId, b.companyName as companyName, 'privateMessage' AS actionType, messageDate AS actionDate
            FROM  `messages` m
            LEFT JOIN `reviews` r ON r.id = m.reviewId
            LEFT JOIN `billing_info` b ON b.userId = r.businessId
            WHERE m.accountManagerId = ".$this->id.str_replace('actionDate', 'messageDate', $queryFilters);

        $sql = implode(' UNION ', array_values($sql));

        $count=Yii::app()->db->createCommand('SELECT COUNT(*) FROM ('.$sql.') x')->queryScalar();

        $dataProvider = new CSqlDataProvider($sql, array(
            'totalItemCount'=>$count,
            'sort'=>array(
                'defaultOrder' => 'actionDate DESC',
                'attributes'=>array(
                     'companyName', 'actionType', 'actionDate',
                ),
            ),
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));
        return $dataProvider;
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                array('jrId, clientId, dateRange', 'safe'),
            )
        );
    }

    public function attributeLabels()
    {
        return array(
            array('clientId', 'safe')
        );
    }

    public function getActionTypesLabels()
    {
        return array(
            'publicComment' => 'Public Comment',
            'flag' => 'Flag',
            'privateMessage' => 'Private Message'
        );
    }
}