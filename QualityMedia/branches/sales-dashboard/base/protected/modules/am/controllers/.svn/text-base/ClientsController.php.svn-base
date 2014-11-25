<?php
/**
 * Controller for handling various client actions
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

class ClientsController extends AmController
{
    /**
     * Default action for this controller, shows a list of clients along with there notes
     *
     * @access public
     * @return void
     */
    public function actionIndex()
    {
        $accountManager = $this->loadModel(Yii::app()->getUser()->getId(), 'AccountManager');

        $model = new Client('search');
        $model->unsetAttributes();

        if(isset($_GET['Client'])) {
            $model->setAttributes($_GET['Client']);
        }

        if ($accountManager->showOnlyLinkedFeeds == 1) {
            $model->accountManager(Yii::app()->getUser()->getId());
        }

        $this->render('index', array(
            'model'=>$model,
        ));
    }

    /**
     * Render available yelp businesses column.
     * @param object $model Client model
     * @param integer $row Row number
     * @return string Cell content
     */
    protected function renderBusinessesColumn($model, $row)
    {
        if($model->profile === null) {
            return '';
        }

        $businesses = array();
        foreach($model->profile->yelpBusinesses as $yelpBusiness) {
            if(!$yelpBusiness->isActive()) {
                continue;
            }

            $businesses[] = CHtml::link(
                Text::truncateChars($yelpBusiness->label, 20),
                $yelpBusiness->getYelpProfileLink(),
                array(
                    'class'=>'tooltipTrigger',
                    'target'=>'_blank',
                    'title'=>$yelpBusiness->label,
                )
            );
        }

        if(count($businesses)) {
            return implode('<br />', $businesses);
        }
        else {
            return 'No active businesses';
        }
    }

    /**
     * Render activity stats column.
     * @param object $model Client model
     * @param integer $row Row number
     * @return string Cell content
     */
    protected function renderActivityColumn($model, $row)
    {
        if(count($model->activity) == 0) {
            return 'Pending';
        }

        $businesses = array();
        foreach($model->activity as $yelpAnalytics) {
            if(!$yelpAnalytics->yelpBusiness->isActive()) {
                continue;
            }

            $businesses[] = CHtml::link(
                Text::truncateChars($yelpAnalytics->yelpBusiness->label, 20),
                array('activity/view','id'=>$yelpAnalytics->id),
                array('class'=>'tooltipTrigger','title'=>$yelpAnalytics->yelpBusiness->label)
            );
        }

        if(count($businesses)) {
            return implode('<br />', $businesses);
        }
        else {
            return 'No active businesses';
        }
    }

    /**
     * Render business info column.
     * @param object $model Client model
     * @param integer $row Row number
     * @return string Cell content
     */
    protected function renderBusinessInfoColumn($model, $row)
    {
        if(count($model->info) == 0) {
            return 'Pending';
        }

        $businesses = array();
        foreach($model->info as $info) {
            if(!$info->yelpBusiness->isActive()) {
                continue;
            }

            $businesses[] = CHtml::link(
                Text::truncateChars($info->yelpBusiness->label, 20),
                array('manage/index','id'=>$info->id),
                array('class'=>'tooltipTrigger','title'=>$info->yelpBusiness->label)
            );
        }

        if(count($businesses)) {
            return implode('<br />', $businesses);
        }
        else {
            return 'No active businesses';
        }
    }

    /**
     * Render photos column.
     * @param object $model Client model
     * @param integer $row Row number
     * @return string Cell content
     */
    protected function renderPhotosColumn($model, $row)
    {
        if(count($model->photos) == 0) {
            return 'Pending';
        }

        $businesses = array();
        foreach($model->photos as $photo) {
            if(!$photo->yelpBusiness->isActive()) {
                continue;
            }

            $businesses[] = CHtml::link(
                Text::truncateChars($photo->yelpBusiness->label, 20),
                array('photos/index','id'=>$photo->yelpBusinessId),
                array('class'=>'tooltipTrigger','title'=>$photo->yelpBusiness->label)
            );
        }

        if(count($businesses)) {
            return implode('<br />', $businesses);
        }
        else {
            return 'No active businesses';
        }
    }
}