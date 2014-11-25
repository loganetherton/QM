<?php $this->setPageTitle('Success'); ?>
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

        <div id="content">
            <div class="container">
                <div id="success-page" class="overlay">
                    <h2>&nbsp;</h2>
                    <div class="dtl">
                        <p>Your payment<br />has been processed successfully.</p>

                        <?php echo CHtml::link('Back to Home', '/'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>