<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Web Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		
	
		'yelpsrch'=>array( 
			'class'=>'YelpComponent',
			/*'consumerKey'=>'zNzqxQjHi-XR99SYzNxl4g',
			'consumerSecret'=>'Jyp7VoIpzjvm-L4HMTbLNPLhzos',
			'token'=>'Lz9SmVzeLgooOu0kkmVgjc1z5VQ3jETQ',
			'tokenSecret'=>'VK2UL_rWDHEBn-eELWz88ngGynI',

			'consumerKey'=>'RaG1FPQisSh_5_UBDdf2ZA',
			'consumerSecret'=>'LBfa7_LAI2Vqx2uJ83BQp1SIhl8',
			'token'=>'zrxfL4aXkA28SesXYg-CmzFsCM3VO-R-',
			'tokenSecret'=>'BOUBR6y4JOIFaZhSsiMZ71UnU0Y',

			'consumerKey'=>'RLFskhzJhxW8iQecQQVKNw',
			'consumerSecret'=>'zJlrA_R7iftyoW91_8sb9OlKI_k',
			'token'=>'ikZxYuALYHr0-yZP1DddcZpGRzQN8zW_',
			'tokenSecret'=>'2jz-6qrOtdRUnK_BqB25p1-XHn8',*/

			'consumerKey'=>'WjePyA_wSMCNzx1HTPeS4g',
			'consumerSecret'=>'ut0QuNBvVSm9OPvRhyIYyDz_CBU',
			'token'=>'z88KmlW9UhlybZ1WpaRW6wMUFYdWy3nP',
			'tokenSecret'=>'y4a30hDqxceaQu-Bl6lDSuWr63w',
		),

		'yelpdb'=>array(
			'class'=>'YelpDBComponent',
		),
			
		'session' => array (
			'autoStart' => true,
		),
	
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host='.ini_get("mysql.default_host").';dbname=myzappy',
			'emulatePrepare' => true,
			'username' => 'myzappy_user',
			'password' => 'vac84oilmarpiP',
			'charset' => 'utf8',
		),
	
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
		'listPerPage'=> 10,
	),
);