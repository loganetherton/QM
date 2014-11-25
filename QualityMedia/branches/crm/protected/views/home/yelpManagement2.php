<?php
    $this->setPageTitle('Yelp Management');
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
        <link href="<?php echo $this->resourceUrl('css/style-landing.css', 's3'); ?>" rel="stylesheet" />
    </head>

    <body>
        <!-- Wrap all page content here -->
        <div id="wrap">
            <!-- Fixed navbar -->
            <?php $this->renderPartial('/layouts/_header'); ?>
            <!-- Begin page content -->
            <div id="content">
                <div class="container">
                    <center>
                        <h2 class="title-base">Online Reviews Affect <span class="red">YOUR</span> Business!<br /> Start Getting More Customers and Better Reviews Now!</h2>
                        <br />
                    </center>
                    <a id="contact-us" name="contact-us"></a>
                    <div class="signwrap clearfix">
                        <div class="boxed clearfix">
                        <div class="socmed-var2">
                            <h2 class="title-base">Quality Media <span class="red">IMPROVES</span> Your Company Rating on these sites!</h2>
                            <div class="item">
                                <span class="img"><?php echo CHtml::image($this->resourceUrl('images_v2/socmedvar-yelp-small.jpg', 's3'), ''); ?></span>
                                <span class="desc"><h2>Yelp</h2></span>
                            </div>
                            <div class="item">
                                <span class="img"><?php echo CHtml::image($this->resourceUrl('images_v2/socmedvar-fb-small.jpg', 's3'), ''); ?></span>
                                <span class="desc"><h2>Facebook</h2></span>
                            </div>
                            <div class="item">
                                <span class="img"><?php echo CHtml::image($this->resourceUrl('images_v2/socmedvar-gplus-small.jpg', 's3'), ''); ?></span>
                                <span class="desc"><h2>Google Plus</h2></span>
                            </div>
                            <div class="item">
                                <span class="img"><?php echo CHtml::image($this->resourceUrl('images_v2/socmedvar-twitter-small.jpg', 's3'), ''); ?></span>
                                <span class="desc"><h2>Twitter</h2></span>
                            </div>
                            <div class="item">
                                <span class="img"><?php echo CHtml::image($this->resourceUrl('images_v2/socmedvar-more-small.jpg', 's3'), ''); ?></span>
                                <span class="desc"><h2>And More...</h2></span>
                            </div>

                        </div>
                        <div class="signbox2">
                            <h2 class="title-base">Let us show you what we can do for<br /> your business. <strong>Register Now!</strong></h2>
                            <p class="clear"></p>
                            <div class="halfside">
                                <form action="/contact.html" id="contactform">
                                    <input type="hidden" id="formSource" name="source" value="lp2" />
                                    <div class="dl">
                                        <label>Your Name</label>
                                        <div class="inp"><span class="txt"><input type="text" id="name" placeholder="Your Name" /></span></div>
                                    </div>
                                    <div class="dl">
                                        <label>Business Name</label>
                                        <div class="inp"><span class="txt"><input type="text" id="businessName" placeholder="Business Name" /></span></div>
                                    </div>
                                    <div class="dl">
                                        <label>Phone Number</label>
                                        <div class="inp"><span class="txt"><input type="text" id="phone" placeholder="Phone Number" /></span></div>
                                    </div>
                                    <br />
                                    <label>&nbsp;</label>
                                    <div class="dl btnn">
                                        <img src="<?php echo $this->resourceUrl('images_v2/cursor-arrow.png', 's3') ?>" class="pointer" style="left:-70px;top:-6px;" />
                                        <input id="submitForm" type="button" class="btn-getstarted" value="" />
                                    </div>
                                </form>
                            </div>
                        </div>
                        </div>
                    </div>
                    <br />
                    <center>
                        <h2 class="title-base">We Make Sure You Get A Return of Your Investment !</h2>
                        <?php echo CHtml::image($this->resourceUrl('images_v2/img-ss1.png', 's3'), ''); ?>
                    </center>
                    <span class="blank-sep"></span>
                    <div class="serve clearfix">
                        <div class="item">
                            <span class="img"><?php echo CHtml::image($this->resourceUrl('images_v2/serve-img-1.png', 's3'), ''); ?></span>
                            <span class="desc"><p><span class="blue">7 out of 10</span> consumers look<br /> online first for local business<br /> information</p></span>
                        </div>
                        <div class="item">
                            <span class="img"><?php echo CHtml::image($this->resourceUrl('images_v2/serve-img-2.png', 's3'), ''); ?></span>
                            <span class="desc"><p>We will help you to achieve<br /> a higher rating on online<br /> review sites!</p></span>
                        </div>
                        <div class="item last">
                            <span class="img"><?php echo CHtml::image($this->resourceUrl('images_v2/serve-img-3.png', 's3'), ''); ?></span>
                            <span class="desc"><p>Get new business from<br /> mobile users, and reward<br /> your most loyal customers!</p></span>
                        </div>
                    </div>


                    <div class="qwrap">
                        <div class="container">
                            <div class="quote-cnt clearfix">
                                <div class="col3">
                                    <div class="col-ct">
                                        <div class="quote">
                                            <p>Quality Media has helped manage our review sites, which gives us time to focus on the core of our business. Since they have taken over, we have seen a HUGE increase in traffic and positive reviews. The investment has already paid tenfold!</p>
                                        </div>
                                        <div class="sender">
                                            <img src="<?php echo $this->resourceUrl('images_v2/img-quote1.png', 's3') ?>" alt="" align="left"/>
                                            <p>Peddy, PIzza Rustica<span>West Hollywood, CA</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col3">
                                    <div class="col-ct">
                                        <div class="quote">
                                            <p>You guys have engaged with all of our online reviews, and helped increase the frequency of visits because of it! Thanks for managing my account with such care, you guys have done terrific Job!</p>
                                        </div>
                                        <div class="sender">
                                            <img src="<?php echo $this->resourceUrl('images_v2/img-quote2.png', 's3') ?>" alt="" align="left"/>
                                            <p>Cedric T., Sofic Greek Resto<span>Los Angeles, CA</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col3">
                                    <div class="col-ct">
                                        <div class="quote">
                                            <p>Quality Media has helped me realize how important my online reviews are.  My account rep has reached out to all of our reviews, optimized my account, and gotten us more business! This has been an A+ experience so far, thanks!</p>
                                        </div>
                                        <div class="sender">
                                            <img src="<?php echo $this->resourceUrl('images_v2/img-quote3.png', 's3') ?>" alt="" align="left"/>
                                            <p>Tom D., West Coast Tires<span>Los Angeles, CA</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->renderPartial('/layouts/_footer-landingPages'); ?>
    </body>
</html>