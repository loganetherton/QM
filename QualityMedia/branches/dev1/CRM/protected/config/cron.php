<?php

return array (
		'defaultController' => 'cron',
        'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name'=>'Cron',
        'preload'=>array('log'),
         'import'=>array(
                'application.components.*',
                'application.models.*',
        ),
        // application components
        'components'=>array(
				'urlManager' => array(
					'rules' => array(
					  '' => 'controller/cron',
					),
				  ),
		
				'yelpsrch'=>array(
					'class'=>'YelpComponent',
					'consumerKey'=>'zNzqxQjHi-XR99SYzNxl4g',
					'consumerSecret'=>'Jyp7VoIpzjvm-L4HMTbLNPLhzos',
					'token'=>'Lz9SmVzeLgooOu0kkmVgjc1z5VQ3jETQ',
					'tokenSecret'=>'VK2UL_rWDHEBn-eELWz88ngGynI',
		
					/*'consumerKey'=>'RaG1FPQisSh_5_UBDdf2ZA',
					'consumerSecret'=>'LBfa7_LAI2Vqx2uJ83BQp1SIhl8',
					'token'=>'zrxfL4aXkA28SesXYg-CmzFsCM3VO-R-',
					'tokenSecret'=>'BOUBR6y4JOIFaZhSsiMZ71UnU0Y',
		
					'consumerKey'=>'RLFskhzJhxW8iQecQQVKNw',
					'consumerSecret'=>'zJlrA_R7iftyoW91_8sb9OlKI_k',
					'token'=>'ikZxYuALYHr0-yZP1DddcZpGRzQN8zW_',
					'tokenSecret'=>'2jz-6qrOtdRUnK_BqB25p1-XHn8',*/
				),
		
				'yelpdb'=>array(
					'class'=>'YelpDBComponent',
				),
		
				'db'=>array(
					'connectionString' => 'mysql:host='.ini_get("mysql.default_host").';dbname=myzappy',
					'emulatePrepare' => true,
					'username' => 'myzappy_user',
					'password' => 'vac84oilmarpiP',
					'charset' => 'utf8',
				),
				
                'log'=>array(
                        'class'=>'CLogRouter',
                        'routes'=>array(
                                array (
                                        'class'=>'CFileLogRoute',
                                        'logFile'=>'cron.log',
                                        'levels'=>'error, warning',
                                ),
                                array (
                                        'class'=>'CFileLogRoute',
                                        'logFile'=>'cron_trace.log',
                                        'levels'=>'trace',
                                ),
                        ),
                ),
                 
        ),
		
		// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'venu@golivemobile.com,sekar@golivemobile.com,ryan.mangune@golivemobile.com',
	), 
);  