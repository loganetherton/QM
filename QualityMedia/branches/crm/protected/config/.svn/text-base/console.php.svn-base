<?php
/**
 * This is the configuration for yiic console application.
 * Any writable CConsoleApplication properties can be configured here.
 */
$config = array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'QualityMedia Console Application',
    'timeZone'=>'America/Los_Angeles',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
        'application.helpers.*',
        'application.components.daemon.*',
        'application.components.future.*',
        'application.extensions.MongoYii.*',
    ),

    // application components
    'components'=>array(
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                'file'=>array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                'reviews-daemon'=>array(
                    'class'=>'CFileLogRoute',
                    'categories'=>'reviews-daemon',
                    'logFile'=>'reviews-daemon.log',
                ),
            ),
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
        ),
        'db'=>array(
            'connectionString'=>'mysql:host=localhost;dbname=qm_dev',
            'username'=>'root',
            'password'=>'',
        ),
        'lock'=>array(
            'class'=>'CFileCache',
        ),
        'securityManager'=>array(
            'encryptionKey'=>'wvn[xj.]GH4K"UoY>ue9#lB!!6-s]Gtf/L9jrc_?0{`(\zrNp`irO)(DE!$H\L+$',
        ),
        'phantomjs'=>array(
            'class'=>'ext.phantomjs.PhantomJsRunner',
        ),
        'recurly'=>array(
            'class'=>'ext.recurly.RecurlyApi',
            'accountModel'=>'User',
            'billingInfoModel'=>'BillingInfo',
            'subscriptionModel'=>'Subscription',
            'transactionModel'=>'Transaction',
            'invoiceModel'=>'Invoice',
        ),
        'systemNotification'=>array(
            'class'=>'ext.systemNotification.systemNotificationHandler',
        ),
        'mongodb' => array(
            'class' => 'EMongoClient',
            'server' => 'mongodb://localhost:27017',
            'db' => 'yelpAnalyticsStorage',
            'w' => 1,
            'RP' => array('RP_PRIMARY', array()),
        ),
    ),

    'commandMap'=>array(
        'migrate'=>array(
            'class'=>'system.cli.commands.MigrateCommand',
            'migrationTable'=>'migrations',
            'templateFile'=>'application.templates.migration',
            // 'connectionID'=>'migrationDb',
            // 'templateFile'=>'application.views.templates.migrations',
        ),
    ),

    'params'=>array(
        'taskmasters'=>4,       // Number of taskmaster daemons to be run
    ),
);

switch(getenv('APP_ENV')) {
    case 'production':
        $envConfig = require (dirname(__FILE__).'/live.php');
        break;
    case 'development':
        $envConfig = require (dirname(__FILE__).'/dev.php');
        break;
    case 'localhost':
        $envConfig = require (dirname(__FILE__).'/local.php');
        break;
    default:
        $envConfig = array();
        break;
}

return array_replace_recursive($config, $envConfig);

/*
    // Cron jobs
    * * * * * /var/www/base/run reviewsDaemon
    * * * * * /var/www/base/run messagesDaemon public
    * * * * * /var/www/base/run messagesDaemon private
*/