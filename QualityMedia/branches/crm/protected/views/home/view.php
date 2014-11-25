<?php
$defaultDomain = Yii::app()->params['domains']['default'];
$signupDomain = Yii::app()->params['domains']['signup']
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
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
                <div class="container">

                    <div id="image-slides" class="carousel slide">
                        <!-- Indicators -->
                        <!-- ol class="carousel-indicators">
                            <li data-target="#image-slides" data-slide-to="0" class="active"></li>
                            <li data-target="#image-slides" data-slide-to="1"></li>
                            <li data-target="#image-slides" data-slide-to="2"></li>
                        </ol -->
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <div class="item active">
                                <a href="<?php echo sprintf('%s/%s', $signupDomain, ''); ?>">
                                    <?php echo CHtml::image($this->resourceUrl('images_v2/sl1.jpg', 's3'), ''); ?>
                                    <div class="abs2">
                                    <?php echo CHtml::image($this->resourceUrl('images_v2/sign-up-now.png', 's3'), ''); ?>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="<?php echo sprintf('%s/%s', $signupDomain, ''); ?>">
                                    <?php echo CHtml::image($this->resourceUrl('images_v2/sl2.jpg', 's3'), ''); ?>
                                    <div class="abs">
                                    <?php echo CHtml::image($this->resourceUrl('images_v2/sign-up-now.png', 's3'), ''); ?>
                                    </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="<?php echo sprintf('%s/%s', $defaultDomain, 'westcoast'); ?>">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/sl3.png', 's3'), ''); ?>
                                <div class="abs">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/learn-more.png', 's3'), ''); ?>
                                </div>
                                </a>
                            </div>
                            <div class="item">
                                <a href="<?php echo sprintf('%s/%s', $defaultDomain, 'tropic'); ?>">
                                    <?php echo CHtml::image($this->resourceUrl('images_v2/sl4.jpg', 's3'), ''); ?>
                                    <div class="abs">
                                    <?php echo CHtml::image($this->resourceUrl('images_v2/learn-more.png', 's3'), ''); ?>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <!-- Controls -->
                        <a class="left carousel-control" href="#image-slides" data-slide="prev"><span class="icon-prev"></span></a>
                        <a class="right carousel-control" href="#image-slides" data-slide="next"><span class="icon-next"></span></a>
                    </div>

                    <h3 class="heading">OUR PARTNERS</h3>
                    <div class="row partners">
                        <div class="col-md-12 col-sm-12">
                             <div class="clients-block">
                                <div class="container" style="overflow: hidden">
                                    <ul id="scroller">
                                        <li><img width="970" src="<?php echo $this->resourceUrl('images_v2/img-client.png', 's3'); ?>" alt="" /></li>
                                        <li><img width="970" src="<?php echo $this->resourceUrl('images_v2/img-client.png', 's3'); ?>" alt="" /></li>
                                        <li><img width="970" src="<?php echo $this->resourceUrl('images_v2/img-client.png', 's3'); ?>" alt="" /></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(function() {
                            $("#scroller").simplyScroll({
                                autoMode: 'loop',
                                startOnLoad: true,
                                pauseOnHover: false
                            });
                        });
                    </script>

                    <a id="featured-on" name="featured-on"></a>
                    <h3 class="heading">FEATURED ON</h3>
                    <div class="row featured">
                        <div class="col-md-4 center">
                            <div class="img">
                                <a href=" http://www.bloomberg.com/article/2013-09-10/amJ9143TeGkI.html" target="_blank"><?php echo CHtml::image($this->resourceUrl('images_v2/bloomberg.png', 's3'), ''); ?></a>
                            </div>
                            <p><strong>BLOOMBERG.COM</strong></p>
                        </div>
                        <div class="col-md-4 center">
                            <div class="img">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/cnbc.png', 's3'), ''); ?>
                            </div>
                            <p><strong>CNBC INTERNATIONAL</strong></p>
                        </div>
                        <div class="col-md-4 center">
                            <div class="img">
                                <a href="http://www.marketwatch.com/story/need-help-with-yelp-quality-media-can-help-2013-09-10" target="_blank"><?php echo CHtml::image($this->resourceUrl('images_v2/marketwatch.png', 's3'), ''); ?></a>
                            </div>
                            <p><strong>MARKET WATCH</strong></p>
                        </div>
                    </div>

                    <a id="what-we-do" name="what-we-do"></a>
                    <h3 class="heading">WHAT WE DO</h3>
                    <div class="row wedo">
                        <div class="col-md-4">
                            <div class="img">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/wedo1.png', 's3'), ''); ?></div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/wedo2.png', 's3'), ''); ?></div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/wedo3.png', 's3'), ''); ?></div>
                        </div>
                    </div>

                    <a id="services" name="services"></a>
                    <h3 class="heading">SERVICES</h3>
                    <div class="row services">
                        <div class="col-md-4">
                            <div class="img">
                                <a href="/products#facebook"><?php echo CHtml::image($this->resourceUrl('images_v2/serv1.png', 's3'), ''); ?></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                                <a href="/products#twitter"><?php echo CHtml::image($this->resourceUrl('images_v2/serv2.png', 's3'), ''); ?></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img no-margin">
                                <a href="/products#emailcampaigns"><?php echo CHtml::image($this->resourceUrl('images_v2/serv3.png', 's3'), ''); ?></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                                <a href="/products#googleplus"><?php echo CHtml::image($this->resourceUrl('images_v2/serv4.png', 's3'), ''); ?></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                                <a href="/products"><?php echo CHtml::image($this->resourceUrl('images_v2/serv5.png', 's3'), ''); ?></a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img no-margin">
                                <a href="/products#yelp"><?php echo CHtml::image($this->resourceUrl('images_v2/serv6.png', 's3'), ''); ?></a>
                            </div>
                        </div>
                    </div>

                    <a id="our-customers" name="our-customers"></a>
                    <h3 class="heading">OUR CUSTOMERS</h3>
                    <div class="row ocust">
                        <div class="col-md-9 col-sm-9">
                            <div class="video">
                                <!-- iframe id="vidfr" width="100%" height="500" src="images/sample-vid.jpg" frameborder="0" allowfullscreen></iframe -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="vid1">
                                        <div class="vidr">
                                            <embed id="playerid"
                                            width="820" height="460"
                                            allowfullscreen="true" allowscriptaccess="always" quality="high"
                                            bgcolor="#000000" name="playerid" style=""
                                            src="http://www.youtube.com/v/FolnfaKlYLc?rel=0&autoplay=0&enablejsapi=1&version=3&playerapiid=ytplayer" type="application/x-shockwave-flash">
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="vid3">
                                        <div class="vidr">
                                            <embed id="playerid"
                                            width="820" height="460"
                                            allowfullscreen="true" allowscriptaccess="always" quality="high"
                                            bgcolor="#000000" name="playerid" style=""
                                            src="http://www.youtube.com/v/vk5pdg0ExPI?rel=0&autoplay=0&enablejsapi=1&version=3&playerapiid=ytplayer" type="application/x-shockwave-flash">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 tabch">
                            <a data-toggle="tab" href="#vid1" class="tb tabv1"><?php echo CHtml::image($this->resourceUrl('images_v2/wcoast-logo.png', 's3'), ''); ?><div class="arrow-down blue"></div></a>
                            <a data-toggle="link" href="<?php echo sprintf('%s/%s', $defaultDomain, 'westcoast'); ?>" class="tabv2"><?php echo CHtml::image($this->resourceUrl('images_v2/wcoast-article.png', 's3'), ''); ?></a>
                            <a data-toggle="tab" href="#vid3" class="tb tabv3"><?php echo CHtml::image($this->resourceUrl('images_v2/tropic-logo.png', 's3'), ''); ?><div class="arrow-down green"></div></a>
                            <a data-toggle="link" href="<?php echo sprintf('%s/%s', $defaultDomain, 'tropic'); ?>" class="tabv4"><?php echo CHtml::image($this->resourceUrl('images_v2/tropic-article.png', 's3'), ''); ?></a>
                        </div>
                    </div>

                    <a id="why-we-do-it" name="why-we-do-it"></a>
                    <h3 class="heading">WHY WE DO IT</h3>
                    <div class="row why-we-do-it">
                        <div class="col-md-3">
                            <div class="img">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/doit1.png', 's3'), ''); ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="img">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/doit2.png', 's3'), ''); ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="img">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/doit3.png', 's3'), ''); ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="img">
                                <?php echo CHtml::image($this->resourceUrl('images_v2/doit4.png', 's3'), ''); ?></div>
                        </div>
                    </div>

                    <a id="about-us" name="about-us"></a>
                    <h3 class="heading">ABOUT US</h3>
                    <div class="row about-box">
                        <?php echo CHtml::image($this->resourceUrl('images_v2/about-us.png', 's3'), '', array('class' => 'img-responsive')); ?>
                        <div class="col-md-12 about-txt">
                            <p>Quality Media has worked with <b>over 2000 local businesses</b> just like yours.<br/>Our team is a mix of social media experts, and web marketing gurus that are focused on<br /> helping you to achieve results in a cost-effective way.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->renderPartial('/layouts/_footer'); ?>
        <script type="text/javascript">
            $(function () {
                $('.carousel').carousel({
                    interval: 4000
                });

            $('.tabch a').click(function() {
                var activeBg = $(this).css('background-color');

                $('.ocust .video').css('background-color', activeBg);
            });

            });

            /*
            * Resize Embed player
            */
            $(document).ready(function(){
                Involved.init();
            });

            $(window).resize(function(){
                Involved.resizeVideoEmbeds();
            });

            var Involved = function(){
                function init(){
                    resizeVideoEmbeds();
                }

                function resizeVideoEmbeds(){
                    var container_width = $(".vidr").width();
                    var orig_width = 0;
                    var orig_height = 0;
                    var ratio = 0;

                    $("#playerid").each(function(){
                        orig_width = $(this).attr("width");
                        orig_height = $(this).attr("height");
                        ratio = (orig_height/orig_width).toFixed(2);
                        $(this).width(container_width);
                        $(this).height(ratio*container_width);
                        console.log(orig_height);
                    });
                }

                return{
                    init: init,
                    resizeVideoEmbeds: resizeVideoEmbeds
                }
            }();
        </script>
    </body>
</html>
