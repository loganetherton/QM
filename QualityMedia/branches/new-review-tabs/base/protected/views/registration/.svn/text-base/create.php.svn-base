<?php $this->setPageTitle('Sign up'); ?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/normalize.css?v=1', 's3'); ?>" type="text/css" charset="utf-8"/>
    <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/splash.css?v=1', 's3'); ?>" type="text/css" charset="utf-8"/>
    <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/recurly.css?v=1', 's3'); ?>" type="text/css" charset="utf-8"/>
    <!--[if IE 6]>
        <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/ie6.css?v=1', 's3'); ?>" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 7]>
        <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/ie7.css?v=1', 's3'); ?>" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 8]>
        <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/ie8.css?v=1', 's3'); ?>" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->

    <link rel="icon" href="/favicon.png" type="image/png" />

    <script type="text/javascript" src="<?php echo $this->resourceUrl('/js/jquery.placeholder.min.js', 's3'); ?>"></script>
</head>
<body>
    <div id="page">
        <div id="header">
            <div class="container clearfix">
                <h1 class="logo left">
                    <?php echo CHtml::image($this->resourceUrl('images/logo.png', 's3'), Yii::app()->name); ?>
                </h1>

                <div class="call right">Call Now! 1-888-435-5518</div>
            </div>
        </div><!-- end header -->

        <div id="content" class="billing">
            <div class="main-reg">
                <div id="recurly-form" class="regbox">
                    <!-- recurly form goes here -->
                    <?php
                        // Recurly form goes here
                        $recurlyApi = Yii::app()->getComponent('recurly');
                        $successPage = $this->createUrl('registration/success');

                        $username = array();
                        if(isset($salesman)) {
                            $username['salesman'] = $salesman->username;
                        }
                        if(isset($accountManager)) {
                            $username['accountManager'] = $accountManager->username;
                        }

                        $this->widget('ext.recurly.RecurlyWidget', array(
                            'config'=>array(
                                'subdomain'=>$recurlyApi->subdomain,
                                'currency'=>$recurlyApi->currency,
                                'locale'=>array(
                                    'errors'=>array(
                                        'acceptTOS'=>'Please accept the agreement',
                                    ),
                                ),
                            ),
                            'subscriptionForm'=>array(
                                'target'=>'#recurly-form',
                                'planCode'=>isset($plan) ? $plan->planCode : $recurlyApi->planCode,
                                'currency'=>$recurlyApi->currency,
                                'enableCoupons'=>false,
                                'collectCompany'=>true,
                                'termsOfServiceURL'=>'#agreement',
                                'successHandler'=>"js:function(token){ window.location='{$successPage}'; }",
                                'afterInject'=>'js:function(formEl){ jQuery("input[placeholder], textarea[placeholder]").placeholder(); }',
                                'account'=>array(
                                    'username'=>count($username) ? CJSON::encode($username) : '',
                                ),
                            ),
                        ));
                    ?>
                </div>
            </div>
        </div> <!-- end content -->

        <div id="footer">
            <div class="container clearfix">
                <p>
                    QualityMedia &copy; 2013
                    <a href="#">About</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms & Conditions</a>
                    <a href="#">Help</a>
                </p>
            </div>
        </div><!-- end footer -->
    </div>

    <div class="pop-overlay" style="display:none;"></div>

    <div class="pop-wrap" style="display:none;">
        <div class="pop-inner">
            <div class="pophead">
                <a href="#" class="close"></a>
            </div>

            <div id="slider">
                <ul style="padding:0px;">
                    <li>
                        <div id="pop-ct1" class="pop-ct">
                            <div class="ct-bd">
                                <div class="regbox">
                                    <div class="reg-hd2"><strong>QualityMedia</strong> Terms and Conditions for Products and Services</div>
                                    <div class="reg-terms clearfix">
                                        <div class="half left">
                                            <h3>THESE TERMS AND CONDITIONS (THE "TERMS" OR "TERMS OF USE") GOVERN YOUR ACCESS TO AND USE OF QUALITY MEDIA'S WEBSITES AND YOUR PURCHASES OF PRODUCTS AND SERVICES FROM QUALITYMEDIA, INC. ("QUALITY MEDIA", THE "COMPANY", "WE", OR "OUR").THIS AGREEMENT DEFINES THE RELATIONSHIP BETWEEN QUALITY MEDIA INC. AND YOU ("YOU", "YOUR", THE "CLIENT"). IF YOU ARE ENTERING INTO THIS AGREEMENT ON BEHALF OF A COMPANY OR OTHER LEGAL ENTITY, YOU ALSO REPRESENT THAT YOU HAVE THE AUTHORITY TO BIND SUCH ENTITY TO THESE TERMS, IN WHICH CASE THE TERMS "YOU", "YOUR" OR "CLIENT" SHALL REFER TO SUCH ENTITY.</h3>
                                            <br />
                                            <p><strong>SERVICES.</strong></p>
                                            <p><strong>1:1 Description.</strong> We provide online review management and privacy related products and services ("Services") for you or someone that you have designated to be the subject of the Services and for whom you will be held strictly responsible (the "Named Party").</p>
                                            <br />
                                            <p><strong>1:2 Online Reviews and Ratings.</strong> We are monitoring and managing your online reviews or ratings as part of your Services, as such, you represent and warrant that: (a) you are authorized to provide us with any customer, patient, and user information that you provide to us in connection with such Services (the "Reviewer Information"), including any personally identifying information of those parties; (b) our possession and/or use of the Reviewer Information on your behalf in connection with the Services will not violate any contract, statute, or regulation; and (c) any content that you and/or your authorized representative(s) submit for publication on an online review or ratings website as a provider of goods or services will be true and accurate, are the original work of your authorship, and will only concern you and the goods and/or services that you provide.</p>
                                        </div>

                                        <div class="half right">
                                            <p><strong>USE OF SITE AND SERVICES.</strong></p>
                                            <p><strong>2:1 User Accounts and Passwords.</strong> Certain features or services offered on or through the Site may require you to open an account (including setting up a QualityMedia.com ID and/or password(s)). You are entirely responsible for maintaining the confidentiality of the information you hold for your account, including your login ID and password, and for any and all activity that occurs under your account as a result of your failing to keep this information secure and confidential. You agree to notify us immediately of any unauthorized use of your account or password, or any other breach of security.</p>
                                            <br />
                                            <p><strong>FEES AND PAYMENT FOR SERVICES.</strong></p>
                                            <p>
                                                <strong>3:1 Fees and Auto-Renewal.</strong>
                                                You agree to pay all fees specified on your accepted Order(s).
                                                You are responsible for providing complete and accurate billing and contact information to us and for notifying us of any changes to such information.
                                                Except as otherwise specified herein or on an Order, all payment obligations are non-cancelable and all fees paid are non-refundable.
                                                You understand and accept that, unless otherwise expressly stated on the applicable Order, our Services are subscriptions services that operate on an auto-renewal basis such that your credit card, debit card, electronic payment, or other method of payment ("Accounts") will be assessed the specified fees at regular intervals based on your subscription program (e.g. annually, quarterly, monthly). The fees for each renewal term will be equal to the fees for the immediately prior term, unless we notify you at least thirty (30) days prior to such renewal of a change to the fees. You represent and warrant that you have the legal rights to use the Accounts and hereby authorize us to charge your Accounts for all Services listed on the Order(s) for the initial subscription term and each renewal term. Such charges shall be made in advance, either annually or in accordance with any different billing frequency stated in the applicable Order. You may cancel this month to month service at anytime with 30 days notice to Quality Media.
                                                After such period, Quality Media will no longer work on your account, and your card will not be billed.</p>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.agreement-page').live('click', function(){
                $(".pop-overlay").show();
                $(".pop-wrap").show();

                return false;
            });

            $(".close").click(function(){
                $(".pop-overlay").hide();
                $(".pop-wrap").hide();
            });
        });
    </script>
</body>
</html>