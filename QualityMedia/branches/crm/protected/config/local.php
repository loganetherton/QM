<?php
return array(
    'name'=>'QualityMedia [local]',
    
    'import'=>array(
        'application.components.*',
        'application.helpers.*',
        'application.models.*',
        
        // These are extensions that I've added
        'ext.YiiMailer.YiiMailer',
        'application.extensions.MongoYii.*',
        //'ext.s3AssetManager.*',
        //'ext.s3AssetManager.lib.*',
    ),

    'components'=>array(
        'db'=>array(
            'connectionString'=>'mysql:host=localhost;dbname=qmdev',
            'username'=>'qm_dev',
            'password'=>'qm_dev',
            'enableParamLogging'=>true,
        ),
        'phantomjs'=>array(
            'class'=>'ext.phantomjs.PhantomJsRunner',
            'bin'=>'phantomjs',
            'scriptPath'=>'/home/logan/public/qm/branches/dev1/core/scraper/phantomjs',
        ),
        //'yiiMailer' => array(
        //    'class' => 'ext.YiiMailer.YiiMailer',
        //),
        'mongodb' => array(
            'class' => 'EMongoClient',
            'server' => 'mongodb://localhost:27017',
            'db' => 'yelpAnalyticsStorage',
            //'db' => 'IDONTEXIST',
            'w' => 1,
            'RP' => array('RP_PRIMARY', array()),
        ),
        //'s3AssetManager' => array(
        //    'class' => 'ext.s3AssetManager.S3AssetManager',
        //),
        //'s3' => array(
        //    'accessKey' => 'AKIAJDDC4MIPTGUY7GOA',
        //    'secretKey' => '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',
        //),
        's3Component' => array(
            'class' => 'ext.s3AssetManager.S3Component',
            'accessKey' => 'AKIAJDDC4MIPTGUY7GOA',
            'secretKey' => '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',
        ),
        's3Resource'=>array(
                'class'=>'ext.amazonS3.AmazonS3Resource',
                'bucket'=>'qm-static',
                'base' => 'http://localhost/qms3/crm/%2$s',
            ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                'file'=>array(
                    'class'=>'CFileLogRoute',
                    //'levels'=>'error, warning, trace, profile',
                    'levels'=>'error, warning, profile, info',
                ),
                'push'=>array(
                    'class'=>'CFileLogRoute',
                    'categories'=>'push',
                    'logFile'=>'push.log',
                ),
                'assets'=>array(
                    'class'=>'CFileLogRoute',
                    'categories'=>'assets',
                    'logFile'=>'assets.log',
                ),
                'request'=>array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'request',
                    'logFile'=>'request.log',
                    'maxLogFiles'=>20,
                ),
                // Mongo log router, works similarly to CDbLogRoute, need to see what's happening at first
                array(
                    'class'=>'EMongoLogRoute',
                    //'connectionId'=>'my_connection_id', // optional, defaults to 'mongodb'
                    'logCollectionName'=>'mongoLog', // optional, defaults to 'YiiLog'
                    'enabled'=>true, // Enable with Yii::app()->log->routes['weblogging']->enabled = true;
                ),
                array(
                    'class'=>'CWebLogRoute',
                ),
            ),
        ),
    ),
); 
?>
