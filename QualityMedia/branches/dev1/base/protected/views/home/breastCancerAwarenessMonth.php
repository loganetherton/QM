<?php
$defaultDomain = Yii::app()->params['domains']['default'];
$signupDomain = Yii::app()->params['domains']['signup']
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

        <?php $this->renderPartial('/layouts/_head'); ?>
    </head>

    <body>
        <!-- Wrap all page content here -->
        <div id="wrap">
            <!-- Fixed navbar -->
            <?php $this->renderPartial('/layouts/_header'); ?>
            <!-- Begin page content -->
            <div id="content">
                <div class="section-wrap">
                    <div class="section ct6 no-border midmargin">
                        <div class="container2">
                            <div class="inner-ct">
                                <div class="ct-dtl services clearfix">
                                    <br/>
                                    <p><span style="font-size:28px"><em><strong>Quality Media</strong> is a proud supporter of <span style="color:#FF7BAC"><strong>BCAM (Breast Cancer Awareness Month)</strong></span>. &nbsp;For every business partnered with Quality Media in the month of october, we will donate 100% of all setup fees to the <span style="color:#FF7BAC"><strong>National Breast Cancer Foundation</strong></span>. Thank you for supporting in the fight to end breast cancer.&nbsp;</em></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container2 img2">
                    <?php echo CHtml::image($this->resourceUrl('images/img-bcam1.jpg', 's3')); ?>
                </div>
            </div><!-- end content -->
        </div>

        <?php $this->renderPartial('/layouts/_footer'); ?>
        <script type="text/javascript">
            $(function () {
                $('.carousel').carousel({
                    interval: 4000
                });

            $('.tabch a').click(function() {
                var activeBg = $(this).css('background');
                console.log(activeBg);
                $('.ocust .video').css('background', activeBg);
            });

            });
        </script>
    </body>
</html>
