<?php
/**
 * This is the main Web application configuration.
 * Any writable CWebApplication properties can be configured here.
 */
$config = array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'QualityMedia',
    'defaultController'=>'home',
    'timeZone'=>'America/Los_Angeles',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.components.*',
        'application.helpers.*',
        'application.models.*',
    ),

    'modules'=>array(
        'admin'=>array(
            'preload'=>array('bootstrap'),
            'components'=>array(
                'bootstrap'=>array(
                    'class'=>'BootstrapS3',
                    'responsiveCss'=>true,
                ),
            ),
        ),
        'am'=>array(
            'preload'=>array('bootstrap'),
            'components'=>array(
                'bootstrap'=>array(
                    'class'=>'BootstrapS3',
                    'responsiveCss'=>true,
                ),
            ),
        ),
        'api',
        'sales'=>array(
            'preload'=>array('bootstrap'),
            'components'=>array(
                'bootstrap'=>array(
                    'class'=>'BootstrapS3',
                    'responsiveCss'=>true,
                ),
            ),
        ),
        'client'=>array(
            'preload'=>array('bootstrap'),
            'components'=>array(
                'bootstrap'=>array(
                    'class'=>'BootstrapS3',
                    'responsiveCss'=>true,
                ),
            ),
        ),
    ),

    // application components
    'components'=>array(
        'user'=>array(
            'class'=>'WebUser',
            'allowAutoLogin'=>true,
        ),
        'urlManager'=>array(
            'urlFormat'=>'path',
            'urlSuffix'=>'.html',
            'showScriptName'=>false,
            'rules'=>array(
                // API REST patterns
                array('api/<controller>/index', 'pattern'=>'api/<controller:\w+>', 'verb'=>'GET'),
                array('api/<controller>/view', 'pattern'=>'api/<controller:\w+>/<id:\w+>', 'verb'=>'GET'),
                array('api/<controller>/create', 'pattern'=>'api/<controller:\w+>', 'verb'=>'POST'),
                array('api/<controller>/update', 'pattern'=>'api/<controller:\w+>/<id:\w+>', 'verb'=>'PUT'),
                array('api/<controller>/delete', 'pattern'=>'api/<controller:\w+>/<id:\w+>', 'verb'=>'DELETE'),

                // Admin sign in / out
                'admin/login'=>'admin/session/create',
                'admin/logout'=>'admin/session/delete',

                // Account manager sign in / out
                'am/login'=>'am/session/create',
                'am/logout'=>'am/session/delete',

                // Salesman sign in / out
                'sales/login'=>'sales/session/create',
                'sales/logout'=>'sales/session/delete',

                // Account manager dashboard
                'am/review/<tab:\w+>'=>'am/review/index',
                'am/message/<tab:\w+>'=>'am/message/index',

                // Subscription forms
                'pricing/s/<salesman:[\w\s-]+>/<plan:[\w-]+>'=>'registration/create',
                'pricing/a/<accountManager:[\w\s-]+>/<plan:[\w-]+>'=>'registration/create',
                'pricing/<plan:[\w-]+>'=>'registration/create',
                'pricing'=>'registration/create',

                'splash'=>'home/splash',
            ),
        ),
        'db'=>array(
            'connectionString'=>'mysql:host=localhost;dbname=testdrive',
            'emulatePrepare'=>true,
            'username'=>'root',
            'password'=>'',
            'charset'=>'utf8',
        ),
        'session'=>array(
            'class'=>'CDbHttpSession',
            'connectionID'=>'db',
            'sessionTableName'=>'session',
        ),
        'errorHandler'=>array(
            'errorAction'=>'error/view',
        ),
        'securityManager'=>array(
            'encryptionKey'=>'wvn[xj.]GH4K"UoY>ue9#lB!!6-s]Gtf/L9jrc_?0{`(\zrNp`irO)(DE!$H\L+$',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                'file'=>array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
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
            ),
        ),
        'assetManager'=>array(
            'class'=>'AssetManager',
        ),
        'clientScript'=>array(
            'scriptMap'=>array(
                // scripts
                'jquery.js'=>'https://qm-static.s3.amazonaws.com/js/jquery.min.js',
                'jquery.ba-bbq.js'=>'https://qm-static.s3.amazonaws.com/js/jquery.ba-bbq.js',
                'jquery.yii.js'=>'https://qm-static.s3.amazonaws.com/js/jquery.yii.js',
                'jquery.yiiactiveform.js'=>'https://qm-static.s3.amazonaws.com/js/jquery.yiiactiveform.js',
                'recurly.min.js'=>'https://qm-static.s3.amazonaws.com/js/recurly-no-address-fields.min.js',
                'jquery.flot.min.js'=>'https://qm-static.s3.amazonaws.com/js/jquery.flot.min.js',

                // styles
                'font-awesome.min.css' =>'https://qm-static.s3.amazonaws.com/css/font-awesome.min.css',
                'entypo.css' =>'https://qm-static.s3.amazonaws.com/css/entypo.css',
                'custom.css' =>'https://qm-static.s3.amazonaws.com/css/custom.css',
                'custom-hendra.css' =>'https://qm-static.s3.amazonaws.com/css/custom-hendra.css',
                'application.css'=>'https://qm-static.s3.amazonaws.com/css/application.css',
            ),
        ),
        'format'=>array(
            'class'=>'UFormatter',
            'datetimeFormat'=>'F d, Y g:ia',
            'dateFormat'=>'F d, Y',
            'timeFormat'=>'g:ia',
        ),
        'widgetFactory'=>array(
            'widgets'=>array(
                'TbGridView'=>array(
                    'baseScriptUrl'=>'https://qm-static.s3.amazonaws.com/zii/gridview',
                ),
                'ClientGridView'=>array(
                    'baseScriptUrl'=>'https://qm-static.s3.amazonaws.com/zii/gridview',
                ),
                'TbListView'=>array(
                    'baseScriptUrl'=>'https://qm-static.s3.amazonaws.com/zii/listview',
                ),
                'CGridView'=>array(
                    'baseScriptUrl'=>'https://qm-static.s3.amazonaws.com/zii/gridview',
                ),
                'CListView'=>array(
                    'baseScriptUrl'=>'https://qm-static.s3.amazonaws.com/zii/listview',
                ),
                'ReviewListView'=>array(
                    'baseScriptUrl'=>'https://qm-static.s3.amazonaws.com/zii/listview',
                ),
                'CDetailView'=>array(
                    'baseScriptUrl'=>'https://qm-static.s3.amazonaws.com/zii/detailview',
                ),
            ),
        ),
        'recurly'=>array(
            'class'=>'ext.recurly.RecurlyApi',
            'accountModel'=>'User',
            'billingInfoModel'=>'BillingInfo',
            'subscriptionModel'=>'Subscription',
            'transactionModel'=>'Transaction',
            'invoiceModel'=>'Invoice',
        ),
        's3Resource'=>array(
            'class'=>'ext.amazonS3.AmazonS3Resource',
            'bucket'=>'qm-static',
        ),
        'bootstrap'=>array(
            'class'=>'BootstrapS3',
            'responsiveCss'=>true,
        ),
        'mailer'=>array(
            'class'=>'ext.mailer.Mailer',
            'dryRun'=>false,
            'layout'=>'email',
            'From'=>'no-reply@qualitymedia.com',
            'FromName'=>'QualityMedia',
        ),
        'phantomjs'=>array(
            'class'=>'ext.phantomjs.PhantomJsRunner',
        ),
    ),

    // custom params
    'params'=>array(
        'contactEmail'=>'dummy@example.com',
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