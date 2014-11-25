<?php
/**
 * Multiple biz accounts init command.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class MultipleBizCommand extends CConsoleCommand
{
    /**
     * Default action.
     */
    public function actionIndex()
    {
        $yelpBusinesses = YelpBusiness::model()->withCredentials()->findAll(array('index'=>'yelpId'));
        $yelpIds = array_keys($yelpBusinesses);

        Yii::import('application.modules.admin.models.YelpAuthenticationForm');

        foreach($yelpBusinesses as $yelpBusiness) {
            try {
                $businesses = $this->callApi($yelpBusiness->profile->yelpUsername, $yelpBusiness->profile->yelpPassword);

                foreach($businesses as $business) {
                    $model = YelpBusiness::model()->findByYelpId($business['yelp_id']);
                    $model = $model === null ? new YelpBusiness : $model;

                    $model->setAttributes(array(
                        'profileId' => $yelpBusiness->profile->id,
                        'userId'    => $yelpBusiness->profile->userId,
                        'yelpId'    => $business['yelp_id'],
                        'bizId'     => $business['id'],
                        'label'     => $business['name'],
                    ));

                    if(!$model->save()) {
                        throw new CException("New biz ({$model->yelpId} / {$model->bizId}) has not been saved");
                    }
                }
            }
            catch(Exception $e) {
                echo $e->getMessage();
            }

            echo "\n";
        }

        echo "\nDone\n";
    }

    /**
     * Call yelp API to get bizId.
     * @param string $username Yelp username
     * @param string $password Yelp password
     * @return string Yelp's Biz id
     */
    protected function callApi($username, $password)
    {
        $model = new YelpAuthenticationForm;
        $model->setAttributes(array(
            'username' => $username,
            'password' => $password,
        ));

        if(!$model->authenticate()) {
            throw new CException("{$username} could not be authenticated");
        }

        return $model->businesses;
    }
}