<?php $this->setPageTitle('Breast Cancer Awareness Month'); ?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>QualityMedia</title>

    <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/normalize.css?v=1', 's3'); ?>" type="text/css" charset="utf-8"/>
    <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/style.css?v=1', 's3'); ?>" type="text/css" charset="utf-8"/>
    <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/scroll.css?v=1', 's3'); ?>" type="text/css" charset="utf-8"/>

    <!--[if IE 6]>
        <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/ie6.css?v=1', 's3'); ?>" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 7]>
        <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/ie7.css?v=1', 's3'); ?>" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->
    <!--[if IE 8]>
        <link rel="stylesheet" href="<?php echo $this->resourceUrl('css/ie8.css?v=1', 's3'); ?>" type="text/css" media="screen" title="style" charset="utf-8" />
    <![endif]-->

    <script type="text/javascript">
        function isValidEmailAddress(emailAddress)
        {
            var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
            return pattern.test(emailAddress);
        }
    </script>

    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.min.js', 's3'); ?>" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/scroll.js', 's3'); ?>" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.placeholder.min.js', 's3'); ?>" charset="utf-8"></script>

    <link rel="icon" href="/favicon.png" type="image/png" />

    <script text="javascript">

        $(document).ready(function() {

            $('input[placeholder], textarea[placeholder]').placeholder();

            $('#signupform #submit').click(function(){
                var hasError = false;

                var nameVal = $('#signupform #name').val();
                if(nameVal == '') {
                    alert('You forgot to enter your name.');
                    hasError = true;
                }

                var phoneVal = $('#signupform #phone').val();
                if(phoneVal == '') {
                    alert('You forgot to enter phone number.');
                    hasError = true;
                }

                if(hasError == false) {
                    $.post('sendemail.php',{ ref:'signup', name: nameVal, phone: phoneVal },function(data){
                        alert('Thank You. A member of our team will contact you shortly.');
                    });
                }
                return false;
            });

            $('#contactform #submit').click(function(){
                var hasError = false;

                var emailVal = $('#contactform #email').val();
                if(emailVal == '') {
                    alert('You forgot to enter the email address.');
                    hasError = true;
                } else if(!isValidEmailAddress(emailVal)) {
                    alert('Enter a valid email address to send to.');
                    hasError = true;
                }

                var nameVal = $('#contactform #name').val();
                if(nameVal == '') {
                    alert('You forgot to enter your name.');
                    hasError = true;
                }

                var messageVal = $('#contactform #message').val();
                if(messageVal == '') {
                    alert('You forgot to enter the message.');
                    hasError = true;
                }

                if(hasError == false) {
                    $.post('<?php echo $this->createUrl('contact/'); ?>',{ ref:'contact', email: emailVal, name: nameVal, message: messageVal },function(data){
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

            $('#scroller').simplyScroll({
                autoMode: 'loop',
                startOnLoad: true,
                pauseOnHover: false
            });

            $('.close-popup').click(function(){
                $('.pop-overlay').hide();
                $('.popup-wrap').hide();
                $('.popup-box').hide();
            });

            $("#back-top").hide();

            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('#back-top').fadeIn();
                } else {
                    $('#back-top').fadeOut();
                }
            });

            // scroll body to 0px on click
            $('#back-top a').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });

        });
    </script>

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

        <div id="header" class="solid">
            <div class="container clearfix">
                <h1 class="logo left"><a href="/"><?php echo CHtml::image($this->resourceUrl('images/logo.png', 's3'), Yii::app()->name); ?></h1></a>
                <div class="menu">
                    <ul class="menuTop">
                        <li><a href="/#what-we-do">What We Do</a></li>
                        <li><a href="/#why-we-do-it">Why We Do It</a></li>
                        <li><a href="/#our-customers">Our Customers</a></li>
                        <li><a href="/products">Products <?php echo CHtml::image($this->resourceUrl('images/ico-arrowmenutop.png', 's3')); ?></a>
                            <ul>
                                <li><a href="/products#facebook"> Facebook</a></li>
                                <li><a href="/products#twitter"> Twitter</a></li>
                                <li><a href="/products#googleplus"> Google+</a></li>
                                <li><a href="/products#yelp"> Yelp</a></li>
                                <li><a href="/products#emailcampaigns"> Email Campaigns</a></li>
                            </ul>
                        </li>
                        <li><a href="/#about">About</a></li>
                        <li><a href="/#contact-us">Contact us</a></li>
                    </ul>
                    <div class="badge" style="position: absolute;right: -100px;top: -10px;"><a href="#"><?php echo CHtml::image($this->resourceUrl('images/badge.png', 's3')); ?></a></div>
                </div>
                <div class="call right">888-435-5518</div>
            </div>
        </div><!-- end header -->

        <div id="content">
            <div class="section-wrap">
                <div class="section ct6 no-border midmargin">
                    <div class="container2">
                        <div class="inner-ct">
                            <div class="ct-dtl services clearfix">
                                <br/>
                                <p><span style="font-size:28px"><em><strong>Quality Media</strong> is a proud supporter of <span style="color:#FF7BAC"><strong>BCAM (Breast Cancer Awareness Month)</strong></span>. &nbsp;For every business partnered with Quality Media in the month of october, we will donate 100% of all setup fees to the <span style="color:#FF7BAC"><strong>National Breast Cancer Foundation</strong></span>. Thank you for supporting in the fight to end breast cancer.&nbsp;</em></span></p>
                                <br />
                                <p><?php echo CHtml::image($this->resourceUrl('images/img-bcam1.jpg', 's3')); ?>
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

                                    $image = CHtml::image($this->resourceUrl('images/ico-googleplus.png', 's3'));
                                    echo CHtml::link($image, 'https://plus.google.com/102089529779650947718/posts');

                                    $image = CHtml::image($this->resourceUrl('images/ico-rss.png', 's3'));
                                    echo CHtml::link($image, '#');
                                ?>
                            </div>
                        <p><a href="mailto:helpdesk@qualitymedia.com">helpdesk@qualitymedia.com</a></p>
                        <p>+1 888 435 5518</p>
                    </div>

                    <div class="left mini-contact">
                        <h3>Contact Us</h3>
                        <p>We would like to hear from you</p>
                        <form action="/contact.html" id="contactform" method="POST">
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
                <p id="back-top"><a href="#tops"><?php echo CHtml::image($this->resourceUrl('images/scrolltop.png', 's3')); ?></a></p>
            </div>
        </div><!-- end footer -->

    </div>
</body>
</html>