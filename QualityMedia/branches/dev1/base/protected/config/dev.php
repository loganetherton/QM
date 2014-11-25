<?php
/**
 * Dev environment config.
 */
return array(
    'name'=>'QualityMedia [dev]',
    'components'=>array(
        'db'=>array(
            'connectionString'=>'mysql:host=qmdbdev.cjircu39c27i.us-west-2.rds.amazonaws.com;dbname=qmdb',
            'username'=>'qm_billing',
            'password'=>'nbEambdlG0Li',
        ),
        'recurly'=>array(
            'apiKey'=>'571452e555134a52b42b4d634901083d',
            'subdomain'=>'qm-dev',
            'privateKey'=>'b45fe30d5c5c4744981ac21804df4f6b',
            'planCode'=>'dev-plan',
            'currency'=>'USD',
        ),
        'clientScript'=> array(
            'scriptMap' => array(
                'recurly-billing-form.min.js'=>'http://qm-static-new.s3.amazonaws.com/js/recurly-billing-form.v2.min.js',
            )
        ),
        'urlManager' => array(
            'rules' => array(
                // DOMAINS SEPARATION
                'http://dev1.signup.qualitymedia.com' => 'registration/choose',
                'http://dev1.signup.qualitymedia.com/order' => 'registration/order',
                'http://dev1.signup.qualitymedia.com/registration/validate' => 'registration/validate',
                'http://dev1.signup.qualitymedia.com/registration/success' => 'registration/success',
                'http://dev1.signup.qualitymedia.com(.*)' => 'error/404',
            )
        ),
        'phantomjs'=>array(
            'class'=>'ext.phantomjs.PhantomJsRunner',
            'bin'=>'/usr/bin/phantomjs',
            'scriptPath'=>'/var/www/core/scraper/phantomjs',
            'proxies'=>array(
                // Elite proxies (brian)
                '23.81.29.127:14638', '23.81.29.191:11235', '23.81.29.12:15388', '23.81.29.20:26039',
                '23.81.29.235:26095', '23.81.29.225:45972', '23.81.29.101:53152', '23.81.29.167:18673',
                '23.81.29.142:27957', '23.81.29.144:20052', '23.81.29.80:61902', '23.81.29.214:52877',
                '23.81.29.55:22956', '23.81.29.200:29204', '23.81.29.186:14791', '23.81.29.166:33028',
                '23.81.29.57:36813', '23.81.29.52:18812', '23.81.29.219:28735', '23.81.29.89:22086',
                '23.81.29.117:61669', '23.81.29.164:17247', '23.81.29.159:51641', '23.81.29.220:62900',
                '23.81.29.228:40944', '23.81.29.131:32931', '23.81.29.93:18399', '23.81.29.59:60173',
                '23.81.29.106:62173', '23.81.29.150:44350', '23.81.29.70:39905', '23.81.29.237:35973',
                '23.81.29.56:54161', '23.81.29.195:60144', '23.81.29.45:44516', '23.81.29.71:15599',
                '23.81.29.18:20885', '23.81.29.60:43682', '23.81.29.43:45855', '23.81.29.134:30947',
                '23.81.29.81:35189', '23.81.29.152:22069', '23.81.29.172:47866', '23.81.29.68:34335',
                '23.81.29.241:21530', '23.81.29.92:29638', '23.81.29.116:15122', '23.81.29.234:36960',
                '23.81.29.197:12365', '23.81.29.2:42938', '23.81.29.86:62384', '23.81.29.157:27650',
                '23.81.29.5:18229', '23.81.29.216:25809', '23.81.29.160:48487', '23.81.29.254:11741',
                '23.81.29.251:21547', '23.81.29.110:35171', '23.81.29.243:50364', '23.81.29.91:11951',
            ),

            'userAgents'=>array(
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:24.0) Gecko/20100101 Firefox/24.0',
                'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20130406 Firefox/23.0',
                'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:23.0) Gecko/20131011 Firefox/23.0',
                'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:22.0) Gecko/20130328 Firefox/22.0',
                'Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20130405 Firefox/22.0',
                'Mozilla/5.0 (Windows NT 6.2; Win64; x64; rv:16.0.1) Gecko/20121011 Firefox/21.0.1',
                'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:16.0.1) Gecko/20121011 Firefox/21.0.1',
            ),
        ),
        'log'=>array(
            'routes'=>array(
                'phantom'=>array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'phantom',
                    'logFile'=>'phantom.log',
                ),
            ),
        ),
    ),
    'params'=>array(
        'contactEmail'=>array('glenn@qualitymedia.com', 'shitiz@qualitymedia.com', 'eric.palces@qualitymedia.com', 'dawid@qualitymedia.com'),

        //Domains separation
        'domains' => array('default' => 'http://dev1.qualitymedia.com', 'signup' => 'http://dev1.signup.qualitymedia.com'),

        'taskmasters'=>1,
    ),
);