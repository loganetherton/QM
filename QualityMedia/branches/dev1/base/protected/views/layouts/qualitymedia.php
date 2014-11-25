<?php
    $defaultDomain = Yii::app()->params['domains']['default'];
    $signupDomain = Yii::app()->params['domains']['signup'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="ico/favicon.png" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!-- Bootstrap core CSS -->
    <link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,400italic' rel='stylesheet' type='text/css'>
    <link href="<?php echo $this->resourceUrl('bootstrap_v3/css/bootstrap.min.qm.css', 's3'); ?>" rel="stylesheet" />
    <!-- Custom styles for this template -->

    <!--[if lt IE 9]>
        <link href="<?php echo $this->resourceUrl('css/ie-8.css', 's3'); ?>" rel="stylesheet" />
        <link href="<?php echo $this->resourceUrl('css/ie-768.css', 's3'); ?>" rel="stylesheet" media="screen and (min-width: 768px)" />
        <link href="<?php echo $this->resourceUrl('css/ie-992.css', 's3'); ?>" rel="stylesheet" media="screen and (min-width: 992px)" />
        <link href="<?php echo $this->resourceUrl('css/ie-1200.css', 's3'); ?>" rel="stylesheet" media="screen and (min-width: 1200px)" />
    <![endif]-->

    <link href="<?php echo $this->resourceUrl('css/style.v2.css', 's3'); ?>" rel="stylesheet" />

    <link rel="icon" href="/favicon.png" type="image/png" />

    <script src="<?php echo $this->resourceUrl('js/jquery.min.js', 's3'); ?>"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="<?php echo $this->resourceUrl('bootstrap_v3/js/html5shiv.js', 's3'); ?>"></script>
        <script src="<?php echo $this->resourceUrl('js/css3-mediaqueries.js', 's3'); ?>"></script>
    <![endif]-->
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.placeholder.min.js', 's3'); ?>"></script>
</head>
<body>
    <!-- Wrap all page content here -->
    <div id="wrap">
        <!-- Fixed navbar -->
        <?php $this->renderPartial('/layouts/_header'); ?>

        <!-- Begin page content -->
        <div id="content">
            <?php echo $content; ?>
        </div>
    </div>

    <?php $this->renderPartial('/layouts/_footer'); ?>

    <script type="text/javascript">
        $(function () {
            $('.carousel').carousel({interval:4000});
        });
    </script>
</body>
</html>