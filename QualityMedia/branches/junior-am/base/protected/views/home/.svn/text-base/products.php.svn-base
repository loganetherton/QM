<?php $this->setPageTitle('Products'); ?>
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

    <div id="page">
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
                        <li><a href="/#our-work">Our Work</a></li>
                        <li><a href="/#about">About</a></li>
                        <li><a href="/#contact-us">Contact us</a></li>
                    </ul>
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
                                <h1 class="ttl">Quality Media Services</h1>
                                <p class="exp">Quality Media is committed to seeing your company succeed. Every customer receives a full-time<br /> dedicated account manager who will oversee the success of your marketing campaigns.</p>
                                <br />
                                <p><?php echo CHtml::image($this->resourceUrl('images/img-services.png', 's3')); ?></p>
                                <h2 class="slg">Grow Your Business with Quality Media!</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section ct3">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="our-customer">BUILD YOUR NETWORK WITH FACEBOOK<a name="facebook"></a></h2>
                            <div class="service-item clearfix">
                                <div class="pull-left">
                                    <h3>Engage with your loyal fans using Facebook.</h3>
                                    <p>Quality Media helps you attract more customers and spread word of mouth marketing.   Our social media experts focus on posting original and exciting content, which help build conversations with your audience.</p>
                                    <ul>
                                        <li>Complete optimization of your business page</li>
                                        <li>Engagement with your audience through daily posts and updates.</li>
                                        <li>Continuous management of your Facebook presence.</li>
                                        <li>Attract more "likes" and fans.</li>
                                    </ul>

                                    <?php echo CHtml::link('Get Started', 'products/choose', array('class'=>'getstarted')); ?>
                                </div>
                                <div class="pull-right">
                                    <?php echo CHtml::image($this->resourceUrl('images/ban-services-fb.png', 's3')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section ct3">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="our-customer">GET NEW CUSTOMERS WITH TWITTER<a name="twitter"></a></h2>
                            <div class="service-item clearfix">
                                <div class="pull-left">
                                    <h3>Twitter is a great way to connect and find new customers!</h3>
                                    <p>Twitter is a great tool to increase brand awareness and give people a chance to learn about your business. Twitter is a fantastic communication channel that can assist with increasing brand loyalty, getting customer feedback, and connecting with customers directly.</p>
                                    <ul>
                                        <li>Complete optimization of your twitter page</li>
                                        <li>Increase fans and followers for your business</li>
                                        <li>Engagement with your audience through exciting posts </li>
                                        <li>Compose special offers</li>
                                    </ul>

                                    <?php echo CHtml::link('Get Started', 'products/choose', array('class'=>'getstarted')); ?>
                                </div>
                                <div class="pull-right">
                                    <?php echo CHtml::image($this->resourceUrl('images/ban-services-tw.png', 's3')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section ct3">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="our-customer">BUILD YOUR NETWORK WITH GOOGLE PLUS<a name="googleplus">&nbsp;</a></h2>
                            <div class="service-item clearfix">
                                <div class="pull-left">
                                    <h3>Google Plus, a great tool for content marketing and building communities.</h3>
                                    <p>Google Plus for businesses is a great way to increase traffic to your business across all Google products. Some of the many products that will be enhanced via google plus are Google searches, Google maps, and many more.  In addition to enhancing traffic to your brand, it is also a great way to build communities around your product or service.</p>
                                    <ul>
                                        <li>Complete optimization of your Google Plus business page (helping others find you).</li>
                                        <li>Engagement with your audience through daily posts and updates.</li>
                                        <li>Building communities around your brand.</li>
                                        <li>Attracting more followers to your business page.</li>
                                    </ul>

                                    <?php echo CHtml::link('Get Started', 'products/choose', array('class'=>'getstarted')); ?>
                                </div>
                                <div class="pull-right">
                                    <?php echo CHtml::image($this->resourceUrl('images/ban-services-gplus.png', 's3')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section ct3">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="our-customer">GET PROPER ONLINE REVIEW MANAGEMENT<a name="yelp"></a></h2>
                            <div class="service-item clearfix">
                                <div class="pull-left">
                                    <h3>Harness the Power of Yelp.</h3>
                                    <p>With so many businesses to choose from, customers need a reason to choose yours. When someone researches your business online, the reviews that they see will have an impact on their decision.</p>
                                    <ul>
                                        <li>Complete management of your Yelp presence.</li>
                                        <li>Respond to all reviews in real time.</li>
                                        <li>Flag inappropriate reviews.</li>
                                        <li>Optimized profile and photo.</li>
                                    </ul>

                                    <?php echo CHtml::link('Get Started', 'products/choose', array('class'=>'getstarted')); ?>
                                </div>
                                <div class="pull-right">
                                    <?php echo CHtml::image($this->resourceUrl('images/ban-services-yelp.png', 's3')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section ct3">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="our-customer">REACH OUT TO YOUR CUSTOMERS WITH EMAIL CAMPAIGNS<a name="emailcampaigns"></a></h2>
                            <div class="service-item clearfix">
                                <div class="pull-left">
                                    <h3>Why is an Email Campaign so important?</h3>
                                    <p>Email campaigns serve a multitude of purposes. From raising brand awareness, to enagging with your audience, to offering specific promotions, emails are great funnel of communication.</p>
                                    <ul>
                                        <li>Build brand awareness.</li>
                                        <li>Increase frequency of visits and sales.</li>
                                        <li>Strengthen customer relationships.</li>
                                        <li>Remind your customer who you are.</li>
                                        <li>Lead generation.</li>
                                        <li>Measure the results</li>
                                    </ul>

                                    <?php echo CHtml::link('Get Started', 'products/choose', array('class'=>'getstarted')); ?>
                                </div>
                                <div class="pull-right">
                                    <?php echo CHtml::image($this->resourceUrl('images/ban-services-email-new.png', 's3')); ?>
                                </div>
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