<?php
$defaultDomain = Yii::app()->params['domains']['default'];
$signupDomain = Yii::app()->params['domains']['signup']
?>

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
                <a class="navbar-brand" href="/"><?php echo CHtml::image($this->resourceUrl('images_v2/logo.png', 's3'), ''); ?></a>
            </div>

            <?php $this->renderPartial('/layouts/_topmenu'); ?>
        </div>
    </div>