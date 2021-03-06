<?php

// Adding Kint for myself, don't commit
require_once(getcwd() . '/../../../kint/Kint.class.php');

// change the following paths if necessary
$yii= realpath('../dev1/core/framework/yii.php');
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',(getenv('APP_ENV') != 'production'));
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();