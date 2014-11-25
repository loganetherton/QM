<?php

class CronController extends Controller 
{
		public $defaultAction = 'cron';
        public function actionCron()
        {
           // Do stuff
		   //process the db contents and parse the contents
  		   $cronList = Yii::app()->yelpdb->actionSendMail();  
        }
}