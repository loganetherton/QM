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
        <link href="<?php echo $this->resourceUrl('bootstrap_v3/css/bootstrap.min.css', 's3'); ?>" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="<?php echo $this->resourceUrl('css/style.v2.css', 's3'); ?>" rel="stylesheet" />

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="bootstrap/js/html5shiv.js"></script>
            <script src="bootstrap/js/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo $this->resourceUrl('js/jquery.min.js', 's3'); ?>"></script>
        <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.placeholder.min.js', 's3'); ?>"></script>
    </head>

    <body>
        <!-- Wrap all page content here -->
        <div id="wrap">
            <!-- Fixed navbar -->
            <div id="header" class="navbar navbar-default">
                <div class="container clearfix">
                    <div class="navbar-header center">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="/">
                            <?php echo CHtml::image($this->resourceUrl('images_v2/logo.png', 's3'), ''); ?></a>
                    </div>
                    <div class="phone pull-right">888-435-5518</div>

                    <?php $this->renderPartial('/layouts/_topmenu'); ?>
                </div>
            </div>
            <!-- Begin page content -->
            <div id="content">
                <div class="container">
                    <div class="section-wrap succwrap">

                        <div class="section ct6 no-border midmargin">
                            <div class="container2">
                                <div class="inner-ct">
                                    <!-- success begin -->
                                    <div class="successbox">
                                        <div class="head"><center><?php echo CHtml::image($this->resourceUrl('images_v2/logo.png', 's3'), 'Qualitymedia', array('width' => 200)); ?></center></div>
                                        <div class="inn">
                                            <h2>Congratulations!<br />  Your Registration was Successful!</h2>
                                            <a href="<?php echo Yii::app()->params['domains']['default']; ?>" class="back">Back to Home</a>
                                        </div>
                                    </div>
                                    <!-- success end -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php $this->renderPartial('/layouts/_footer'); ?>
    </body>
</html>