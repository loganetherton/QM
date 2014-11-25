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
    <style>

    </style>
    <body>
        <!-- Wrap all page content here -->
        <div id="wrap">
            <!-- Fixed navbar -->
            <?php $this->renderPartial('/layouts/_header'); ?>
            <!-- Begin page content -->
            <div id="content">
                <div class="container page1">
                    <div class="row">
                        <div class="col-md-12 top-head">
                            <img src="<?php echo $this->resourceUrl('images_v2/imgheadbig.jpg', 's3') ?>" class="bg" />
                            <div class="video">
                                <a href="#mediaModal" data-toggle="modal"><img src="<?php echo $this->resourceUrl('images_v2/imgheadtv1.png', 's3') ?>"/></a>

                                <!-- Video / Generic Modal -->
                                <div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <div class="modal-body">
                                                <embed id="playerid"
                                                width="820px" height="460px"
                                                allowfullscreen="true" allowscriptaccess="always" quality="high"
                                                bgcolor="#000000" name="playerid" style=""
                                                src="http://www.youtube.com/v/vk5pdg0ExPI?rel=0&autoplay=1&enablejsapi=1&version=3&playerapiid=ytplayer" type="application/x-shockwave-flash">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="text">
                                <h2>THE TROPICS</h2>
                                <h5>Unlike no other plant nursery you will ever see.</h5>
                            </div>
                        </div>
                    </div>
                    <div class="container2">
                        <div class="padding-50">
                        <div class="big-left">R</div>
                        <p>ight in the heart of Hollywood, CA,  at the corner of Santa Monica and La Brea, there is a special place. It is call The Tropics Inc. Although to owner Ron Hroziencik, his son Ryan, the small crew of workers, and thousands of the most amazing trees and plants in southern California, this is home. </p>

    <p>The Tropics Inc opened in 1972 as a small plant store mostly offering your basic houseplants. Over the years, and through a lot of hard work, Ron has grown his store into something much more. There  is now over 20,000 sq ft. of exotic trees, plants, hard to find corral, and other accessories to turn any home or workplace into your own private oasis. </p>
                        </div>
                    </div>
                    <hr />

                    <div class="container2 quote2">
                        <img src="<?php echo $this->resourceUrl('images_v2/qleft.png', 's3') ?>" class="q-left" />
                        <img src="<?php echo $this->resourceUrl('images_v2/qright.png', 's3') ?>" class="q-right" />
                        <p>We are very busy, sometimes overwhelmingly. Quality Media does a great job with protecting our reputation online.</p>
                    </div>
                    <hr />
                    <div class="container2 img2">
                        <?php echo CHtml::image($this->resourceUrl('images_v2/img2.jpg', 's3'), ''); ?>
                    </div>
                    <div class="container2">
                        <div class="padding-50 gentiumbb-regular" style="padding-top:20px;padding-bottom:20px">
                            <p align="justify">Ron's son, Ryan, is a very gifted artist and has used his talents to make The Tropics one of the most sought after "plantscaping" businesses in the industry. With an eye for design that is unmatched, Ryan has been able to capture exactly what their clients need, and most times exceeds expectations. </p>
                            <p align="justify">If some of these plants look familiar it may be because a large number of TV and Film productions use The Tropics as their go-to for anything foliage. You can see their plants in films like 'Jurassic Park', 'Batman', 'Minority Report', and 'ET'. Also on the widely popular television show like 'Lost'. The Tropics has also provided amazing designs for all sorts of Hollywood celebrities like Jack Nicholson, Bono, Brad Pitt, Ellen Degeneres, and many more. </p>
                            <p align="justify">The Tropics Inc has taken a huge step in promoting the great work that they do by beginning to utilize social media. Earlier in 2013, The Tropics partnered with Quality Media to manage their online reputation with review sites like Yelp. Seeing the importance for engaging with their fans and clients on other social media platforms, Ron asked Quality Media for help with Facebook, Google+, and Twitter as well. Quality Media not only built attractive pages that clearly define The Tropics brand, but QM also completely manages the sites for the business. Taking a lot of pressure off of Ron and Ryan's shoulders.</p>
                            <p align="justify">"We are very busy, sometimes overwhelmingly. Quality Media does a great job with protecting our reputation online," says Ben Rood, General Manager for The Tropics.</p>
                            <p align="justify">So, if you are ever in the Hollywood area and want to be transported to what is mostly described as, "another world", stop into The Tropics. Say hello to Ron and Ryan. They will teach you a thing or two about exotic plants and welcome all who wish to bask in the beauty which is their shop. </p>
                        </div>
                    </div>
                    <hr />
                    <div class="container2 more-detail3">
                        <div class="padding-50">
                            <h5>FOR MORE INFO ON THE TROPICS INC., CHECK OUT:</h5>
                            <div class="col-md-3"></div>
                            <div class="ph col-md-6" style="text-align: center">
                                <a href="http://www.thetropicsinc.com" target="_blank"><?php echo CHtml::image($this->resourceUrl('images_v2/icon-web.png', 's3'), ''); ?> &nbsp; www.thetropicsinc.com</a>
                                <a style="margin-left: 30px" href="https://www.facebook.com/pages/The-Tropics-Inc/165840833442420" target="_blank"><?php echo CHtml::image($this->resourceUrl('images_v2/icon-fb.png', 's3'), ''); ?> &nbsp; facebook</a>
                            </div>
                            <div class="col-md-3"></div>
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
                })
            })
        </script>
    </body>
</html>