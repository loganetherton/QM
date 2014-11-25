<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/normalize.css?v=2', 's3'); ?>" type="text/css" charset="utf-8"/>
    <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/style.css?v=2', 's3'); ?>" type="text/css" charset="utf-8"/>
    <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/scroll.css?v=2', 's3'); ?>" type="text/css" charset="utf-8"/>

    <!--[if IE 6]>
        <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/ie6.css?v=2', 's3'); ?>" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 7]>
        <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/ie7.css?v=2', 's3'); ?>" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 8]>
        <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/ie8.css?v=2', 's3'); ?>" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->

    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.min.js', 's3'); ?>" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.sudoSlider.js', 's3'); ?>" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/scroll.js', 's3'); ?>" charset="utf-8"></script>

    <style type="text/css">
        #thankyou-popup{position:fixed;top:50%;left:50%;margin-top:-300px;margin-left:-330px;z-index:99;}
    </style>
</head>
<body>
    <!-- popup begin -->
    <div id="thankyou-popup" style="display:none;">
        <div class="pop-overlay"></div>
        <div class="popup-wrap">
            <div class="popup-box">
                <div class="section">
                    <h2 class="heading">THANK YOU!</h2>
                    <center>
                        <h4>A member of our team will contact you shortly.</h4>
                        <a href="#" class="close-popup">Close</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <!-- popup end -->

    <div id="page">
        <div id="header">
            <div class="container clearfix">
                <h1 class="logo left">
                    <?php echo CHtml::image($this->resourceUrl('images/logo.png', 's3'), Yii::app()->name); ?>
                </h1>

                <div class="menu">
                    <a href="#what-we-do">What We Do</a>
                    <a href="#why-we-do-it">Why We Do It</a>
                    <a href="#our-customers">Our Customers</a>
                    <a href="#our-work">Our Work</a>
                    <a href="#about">About</a>
                    <a href="#contact-us">Contact us</a>
                </div>

                <div class="call right">888-435-5518</div>
            </div>
        </div><!-- end header -->

        <div id="slides">
            <div id="slider">
                <ul>
                    <li><?php echo CHtml::image($this->resourceUrl('images/img-slide1.jpg', 's3'), ''); ?></li>
                    <li><?php echo CHtml::image($this->resourceUrl('images/img-slide2.jpg', 's3'), ''); ?></li>
                    <li><?php echo CHtml::image($this->resourceUrl('images/img-slide3.jpg', 's3'), ''); ?></li>
                    <li><?php echo CHtml::image($this->resourceUrl('images/Hairstylist.jpg', 's3'), ''); ?></li>
                </ul>
            </div>

            <div class="slide-text">
                <?php echo CHtml::image($this->resourceUrl('images/slide-txt.png', 's3'), ''); ?>

                <div class="signup-box">
                    <div class="linebox"></div>

                    <?php echo CHtml::image($this->resourceUrl('images/bgline-tl.png', 's3'), '', array('class'=>'tl')); ?>
                    <?php echo CHtml::image($this->resourceUrl('images/bgline-tr.png', 's3'), '', array('class'=>'tr')); ?>

                    <div class="signup-inner">
                        <div class="signup-ct">
                            <form action="<?php echo $this->createUrl('contact/'); ?>" method="POST" id="signupform">
                                <input type="hidden" name="ref" value="signup" />
                                <p class="txt"><input id="name" name="name" type="text" placeholder="Business Name"/></p>
                                <p class="txt"><input id="phone" name="phone" type="text" placeholder="Business Phone Number"/></p>
                                <p class="btn"><input type="submit" value="Sign Up Now" id="submit"/></p>
                            </form>

                        </div>
                    </div>

                    <?php echo CHtml::image($this->resourceUrl('images/bgline-bl.png', 's3'), '', array('class'=>'bl')); ?>
                    <?php echo CHtml::image($this->resourceUrl('images/bgline-br.png', 's3'), '', array('class'=>'br')); ?>

                    <div class="linebox"></div>
                </div>
            </div>

            <div class="slides-bot"></div>
        </div>

        <div id="content">
            <div class="clients-block">
                <div class="container">
                    <ul id="scroller">
                        <li><?php echo CHtml::image($this->resourceUrl('images/img-client.png', 's3'), '', array('width'=>970)); ?></li>
                        <li><?php echo CHtml::image($this->resourceUrl('images/img-client.png', 's3'), '', array('width'=>970)); ?></li>
                        <li><?php echo CHtml::image($this->resourceUrl('images/img-client.png', 's3'), '', array('width'=>970)); ?></li>
                    </ul>
                </div>
            </div>

            <div class="section-wrap">
                <div class="section ct1">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="what-we-do">What we do</h2>

                            <div class="ct-dtl clearfix">
                                <div class="col3">
                                    <div class="col-ct">
                                        <?php echo CHtml::image($this->resourceUrl('images/img1-ct1.png', 's3')); ?>
                                        <h3>Engaging with your audience and reviewers regularly.</h3>
                                        <p>Consistent engagement with your audience and reviewers is the key to higher frequency of visits and word of mouth.</p>
                                    </div>
                                </div>
                                <div class="col3">
                                    <div class="col-ct">
                                        <?php echo CHtml::image($this->resourceUrl('images/img2-ct1.png', 's3')); ?>
                                        <h3>Optimizing your accounts to ensure maximum search exposure.</h3>
                                        <p>Optimization of your accounts leads to higher search exposure and visibility. People are searching for your product or service, make sure to stand out!</p>
                                    </div>
                                </div>
                                <div class="col3">
                                    <div class="col-ct">
                                        <?php echo CHtml::image($this->resourceUrl('images/img3-ct1.png', 's3')); ?>
                                        <h3>Grow Your Business.</h3>
                                        <p>We grow your business by helping you to achieve higher ratings on online review sites, get better search results, and attract more mobile customers.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section ct2">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading">Raising Your Business With Yelp</h2>

                            <div class="ct-dtl clearfix">
                                <p>Responding to online reviews can improve your online reputation and encourage word of mouth. Research proves that a half star increase in your yelp rating is equivalent to 10% to your bottom line annually.  Businesses that are pro-active with their audience tend to see a higher frequency of visits, and a longer customer life.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section ct5">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="why-we-do-it">Why we do it</h2>
                            <div class="ct-dtl clearfix">
                                <p>A larger percentage of consumers and business people <br/>find reviews highly important.</p>

                                <div class="boxed">
                                    <div class="box-ct">
                                        <p>
                                            <span class="img">
                                                <?php echo CHtml::image($this->resourceUrl('images/img1-ct5.png', 's3')); ?>
                                            </span>
                                            <strong>7 out of 10</strong> consumers look online first for local business information
                                        </p>
                                        <p>
                                            <span class="img">
                                                <?php echo CHtml::image($this->resourceUrl('images/img2-ct5.png', 's3')); ?>
                                            </span>
                                            <strong>81%</strong> of consumers say it’s important for businesses to respond to reviews
                                        </p>
                                        <p>
                                            <span class="img">
                                                <?php echo CHtml::image($this->resourceUrl('images/img3-ct5.png', 's3')); ?>
                                            </span>
                                            <strong>65%</strong> of business owners using social media said it helped them stay engaged with current customers
                                        </p>
                                        <p>
                                            <span class="img">
                                                <?php echo CHtml::image($this->resourceUrl('images/img4-ct5.png', 's3')); ?>
                                            </span>
                                            <strong>8 out of 10</strong> consumers are likely to seek the opinions of others online before buying goods
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section ct3">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="our-customers">A Word From Our Clients</h2>
                            <div class="ct-dtl clearfix">
                                <div class="col3" style="width:47%">
                                    <div class="col-ct">
                                        <div class="quote">
                                            <p>Quality Media has helped manage our review sites which gives us time to focus on the core of our business. Since they have taken over, we have seen a HUGE increase in traffic and positive reviews. The investment has already paid tenfold!</p>
                                        </div>
                                        <div class="sender">
                                            <?php echo CHtml::image($this->resourceUrl('images/img-quote1.png', 's3'), '', array('align'=>'left')); ?>
                                            <p>Peddy, PIzza Rustica<span>West Hollywood, CA</span></p>
                                        </div>
                                    </div>
                                </div>
								<?php /*
                                <div class="col3">
                                    <div class="col-ct">
                                        <div class="quote">
                                            <p>You guys have engaged with all of our online reviews and helped increase the frequency of visits because of it! Thanks for managing my account with such care, you guys have done terrific Job!</p>
                                        </div>
                                        <div class="sender">
                                            <?php echo CHtml::image($this->resourceUrl('images/img-quote2.png', 's3'), '', array('align'=>'left')); ?>
                                            <p>Cedric T., Sofic Greek Resto<span>Los Angeles, CA</span></p>
                                        </div>
                                    </div>
                                </div>
								*/ ?>
                                <div class="col3"  style="width:47%;float:right">
                                    <div class="col-ct">
                                        <div class="quote">
                                            <p>My account rep has reached out to all of our reviews, optimized my account, and gotten us more business! This has been an A+ experience so far, thanks!</p>
                                        </div>
                                        <div class="sender">
                                            <?php echo CHtml::image($this->resourceUrl('images/img-quote3.png', 's3'), '', array('align'=>'left')); ?>
                                            <p>Tom D., West Coast Tires<span>Los Angeles, CA</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section ct4">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="our-work">We Do Good Work</h2>
                            <div class="ct-dtl clearfix">
                                <p>We work to get you placed on the first page for keywords, so customers find you before they find your competitors!</p>
                                <?php echo CHtml::image($this->resourceUrl('images/img1-ct4.jpg', 's3')); ?>
                                <?php echo CHtml::image($this->resourceUrl('images/img2-ct4.jpg', 's3')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section ct4">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="about">About</h2>
                            <div class="ct-dtl clearfix">
                                <?php echo CHtml::image($this->resourceUrl('images/img-about.png', 's3')); ?>
                                <p>
                                    Quality Media has worked with over 2000 local businesses just like yours.
                                    <br/>
                                    Our team is a mix of social media experts, and web marketing gurus that are focused on helping you to achieve results in a cost-effective way.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end content -->

        <div id="footer">
            <div class="container clearfix">
                <div class="clearfix">
                    <div class="info left">
                        <?php echo CHtml::image($this->resourceUrl('images/logo1.png', 's3')); ?>

                        <div class="connect" id="contact-us">
                            <span>Connect with us</span>
                            <?php
                                $image = CHtml::image($this->resourceUrl('images/ico-fb.png', 's3'));
                                echo CHtml::link($image, 'https://www.facebook.com/QualityMedia1', array('target'=>'_blank'));

                                $image = CHtml::image($this->resourceUrl('images/ico-tw.png', 's3'));
                                echo CHtml::link($image, 'https://twitter.com/Quality_Media1', array('target'=>'_blank'));

                                $image = CHtml::image($this->resourceUrl('images/ico-rss.png', 's3'));
                                echo CHtml::link($image, '#');
                            ?>
                        </div>

                        <p><?php echo CHtml::mailto('helpdesk@qualitymedia.com', 'helpdesk@qualitymedia.com'); ?></p>

                        <p>+1 888 435 5518</p>
                    </div>

                    <div class="left mini-contact">
                        <h3>Contact Us</h3>
                        <p>We would like to hear from you</p>
                        <form action="<?php echo $this->createUrl('contact/'); ?>" id="contactform" method="POST">
                            <input type="hidden" name="ref" value="contact" />
                            <p class="txt"><input type="text" id="name" name="name" placeholder="Name"/></p>
                            <p class="txt"><input type="text" id="email" name="email" placeholder="Email"/></p>
                            <p class="txt"><textarea id="message" name="message" cols="30" rows="10" placeholder="Message"></textarea></p>
                            <p class="btn"><input type="submit" id="submit" value="Send"/></p>
                        </form>

                    </div>

                    <div class="foot-menu right">
                        <div class="left">
                            <p>Company</p>
                            <a href="#">About Us</a>
                            <a href="#">Support</a>
                            <a href="#">Knowledgebase</a>
                            <a href="#">Legal</a>
                        </div>
                    </div>
                </div>
                <p class="copy">&copy; 2013 QualityMedia</p>
            </div>
        </div><!-- end footer -->
    </div>

    <!-- scripts here -->
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.placeholder.min.js', 's3'); ?>" charset="utf-8"></script>
    <script type="text/javascript">
        $('input[placeholder], textarea[placeholder]').placeholder();

        $(document).ready(function() {
            var sudoSlider = $("#slider").sudoSlider({
                speed:500,
                pause:4000,
                prevNext:false,
                numeric:true,
                autoheight:false,
                auto:true
            });

            function isValidEmailAddress(emailAddress) {
                var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
                return pattern.test(emailAddress);
            }

            $("#signupform #submit").click(function(){
                var hasError = false;

                var nameVal = $("#signupform #name").val();
                if(nameVal == '') {
                    alert('You forgot to enter your name.');
                    hasError = true;
                }

                var phoneVal = $("#signupform #phone").val();
                if(phoneVal == '') {
                    alert('You forgot to enter phone number.');
                    hasError = true;
                }

                if(hasError == false) {
                    $.post("<?php echo $this->createUrl('contact/'); ?>",{ ref:'signup', name: nameVal, phone: phoneVal },function(data){
                        $('#thankyou-popup').show();

                        // Google tracking conversion
                        var google_conversion_id = 995444632;
                        var google_conversion_language = "en";
                        var google_conversion_format = "2";
                        var google_conversion_color = "ffffff";
                        var google_conversion_label = "MPVvCIDZ9QQQmI_V2gM";
                        var google_conversion_value = 0;

                        $.getScript('http://www.googleadservices.com/pagead/conversion.js');
                    });
                }
                return false;
            });

            $("#contactform #submit").click(function(){
                var hasError = false;

                var emailVal = $("#contactform #email").val();
                if(emailVal == '') {
                    alert('You forgot to enter the email address.');
                    hasError = true;
                }
                else if(!isValidEmailAddress(emailVal)) {
                    alert('Enter a valid email address to send to.');
                    hasError = true;
                }

                var nameVal = $("#contactform #name").val();
                if(nameVal == '') {
                    alert('You forgot to enter your name.');
                    hasError = true;
                }

                var messageVal = $("#contactform #message").val();
                if(messageVal == '') {
                    alert('You forgot to enter the message.');
                    hasError = true;
                }

                if(hasError == false) {
                    $.post("<?php echo $this->createUrl('contact/'); ?>",{ ref:'contact', email: emailVal, name: nameVal, message: messageVal },function(data){
                        $('#thankyou-popup').show();

                        // Google tracking conversion
                        var google_conversion_id = 995444632;
                        var google_conversion_language = "en";
                        var google_conversion_format = "2";
                        var google_conversion_color = "ffffff";
                        var google_conversion_label = "MPVvCIDZ9QQQmI_V2gM";
                        var google_conversion_value = 0;

                        $.getScript('http://www.googleadservices.com/pagead/conversion.js');
                    });
                }
                return false;
            });
        })
    </script>

    <script type="text/javascript">
    (function($) {
        $(function() { //on DOM ready
            $("#scroller").simplyScroll({
                autoMode: 'loop',
                startOnLoad: true,
                pauseOnHover: false
            });
        });
    })(jQuery);

    $(document).ready(function() {
        $(".close-popup").click(function(){
            $('#thankyou-popup').hide();
        });
    })
    </script>
</body>
</html>