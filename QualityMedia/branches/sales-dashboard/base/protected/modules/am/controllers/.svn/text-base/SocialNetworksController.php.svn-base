<?php
/*
 * Controller for social network dashboard
 * 
 * @author Nitesh Pandey <nitesh@qualitymedia.com>
 */

class SocialNetworksController extends AmController
{
    public function actionIndex()
    {
        $this->render('index');
    }
    /**
     * Helper to render partials
     */
    public function actionPartial()
    {
        $partialName = Yii::app()->request->getQuery('name');
        
        if(!empty($partialName)){
            return $this->renderPartial('_'.$partialName);
        }
    }
    
    public function actionSearchClient()
    {
        
        $model = new Client('search');
        $model->unsetAttributes();
        if(isset($_GET['q'])){
            $model->setAttribute('companyName', $_GET['q']);
        }

        $model->accountManager(Yii::app()->getUser()->getId());
        $clientsResultArray = array();

        foreach($model->search()->data as $client)
        {
            $clientsResultArray[] = array('name' => $client->billingInfo->companyName,
                                        'twitterUserId' => $client->profile->twitterUserId,
                                        'id'            => $client->profile->id,
                                        'twitter'       => !empty($client->profile->twitterUserId),
                                        'facebook'      => false);
        }
        echo CJSON::encode($clientsResultArray);
    }
    /*
     * Mock Method for displaying tweets
     */
    public function actionGetTwitterUpdates()
    {
        $oauth = Yii::app()->twitter;
        $token = '1705496647-zVHQ4BesSYMXKnrc4wmmblNtcX6UWHdZh0rn3k';
        $tokenSecret = '1wiLgpe04kdopQOBVgNfJMGHYmdiXjqsJkhQ3ufMb8';
        
        $oauth->setToken($token, $tokenSecret);
        header('Content-type: application/json');
        
        $last_status_id = isset($_GET['max_id']) ? $_GET['max_id'] : null;
        $options = array();
        $options['count'] = 20;
        $last_status_id && $options['max_id'] = $last_status_id;
        echo CJSON::encode($oauth->get('statuses/home_timeline', $options));
    }
    
}
?>
