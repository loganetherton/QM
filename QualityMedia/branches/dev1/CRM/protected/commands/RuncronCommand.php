<?php

class RuncronCommand extends CConsoleCommand
{
        public function run($args)
        {
           // Do stuff
		   //process the db contents and parse the contents
		   $cronList = Yii::app()->yelpdb->actionSendMail();  
  		   //$cronList = Yii::app()->yelpdb->actionCron(); 
        }
}