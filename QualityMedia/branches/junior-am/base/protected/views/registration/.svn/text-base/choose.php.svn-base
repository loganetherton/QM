<?php $this->setPageTitle('Choose a Plan');

$plans = $model->getPlans();
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

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
    <style type="text/css">
    .ct6 .bxd .add-plan.btn-selected {background: #3FBDEF;}

    </style>
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.min.js', 's3'); ?>" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/scroll.js', 's3'); ?>" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.customSelect.min.js', 's3'); ?>"></script>
    <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.placeholder.min.js', 's3'); ?>" charset="utf-8"></script>

    <link rel="icon" href="/favicon.png" type="image/png" />

    <script text="javascript">

        var isValidEmailAddress = function(emailAddress) {
            var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
            return pattern.test(emailAddress);
        }

        $(document).ready(function() {

            //placeholder
            $('input[placeholder], textarea[placeholder]').placeholder();

            //contact form submit
            $('#contactform #submit').click(function(e){
                e.preventDefault();
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

            //scroller
            $('#scroller').simplyScroll({
                autoMode: 'loop',
                startOnLoad: true,
                pauseOnHover: false
            });

            //popup
            $('.close-popup').click(function(){
                $('.pop-overlay').hide();
                $('.popup-wrap').hide();
                $('.popup-box').hide();
            })


            //custom select
            $('p.sel select').customSelect();

            //Toggle available plan addons

            $.fn.toggleDisabled = function(){
                return this.each(function(){
                    this.disabled = !this.disabled;
                });
            };

            $('#addonsEmailMarketingSelect').change(function() {
                $('#ProductsFormAddonsEmailMarketing').val($(this).val());

                if($(this).val() == '') {
                    $('a[href=#ProductsFormAddonsEmailMarketing]').removeClass('btn-selected');
                }
                else {
                    $('a[href=#ProductsFormAddonsEmailMarketing]').addClass('btn-selected');
                    $('#noplan-error').hide();
                }
            });

            $('.add-plan').click(function(e) {
                e.preventDefault();
                var obj = $($(this).attr('href'));
                var isDisabled = Boolean(obj.attr('disabled'));
                if(obj.val() == '' && isDisabled) {
                    console.log($(this).hasClass('btn-selected'));
                    $('#noplan-error').show();
                }
                else {
                    obj.toggleDisabled();
                    $('#noplan-error').hide();

                    isDisabled = Boolean(obj.attr('disabled'));

                    if(isDisabled) {
                        $(this).text('Add to plan');
                    }
                    else {
                        $(this).text('Remove Add-on');
                    }
                }
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
        <?php //@todo push that to the style.css file ?>

        /*259*/
        .ct6 .bxd .mail-market {float:left; text-align:left; display:inline-block;padding:8px 0 0 70px; height:40px; font-size:18px; color:#666666; background:url(http://qm-static.s3-website-us-west-2.amazonaws.com/images/mail-market-ico.png) no-repeat; width:650px;}

        /*275*/
        .bill-form .txt input, p.sel .customSelect, p.sel .customSelect {font-family: 'OpenSansRegular'; line-height: 1.2; font-size:14px;}
        p.sel {border-radius:5px; border:2px solid #ececec; width:362px;
            background: #ffffff;
            background: -moz-linear-gradient(top,  #ffffff 0%, #ffffff 19%, #efefef 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(19%,#ffffff), color-stop(100%,#efefef));
            background: -webkit-linear-gradient(top,  #ffffff 0%,#ffffff 19%,#efefef 100%);
            background: -o-linear-gradient(top,  #ffffff 0%,#ffffff 19%,#efefef 100%);
            background: -ms-linear-gradient(top,  #ffffff 0%,#ffffff 19%,#efefef 100%);
            background: linear-gradient(to bottom,  #ffffff 0%,#ffffff 19%,#efefef 100%);
            -pie-background: linear-gradient(#ffffff,#efefef); /* W3C */
            behavior:url(js/PIE.htc);
            position:relative;
        }
        /*288*/
        p.sel .customSelect{padding:8px 8px; width:346px; background:url(http://qm-static.s3-website-us-west-2.amazonaws.com/images/sel-ico.png) no-repeat 342px; color:#808080; -pie-png-fix:true;behavior:url(http://qm-static.s3-website-us-west-2.amazonaws.com/js/PIE.htc);}
        .bill-form .sel.month, .bill-form .sel.day{display:inline-block; width:auto !important; margin-top:0;}
        .bill-form .sel.month .customSelect{width:120px; background:url(http://qm-static.s3-website-us-west-2.amazonaws.com/images/sel-ico.png) no-repeat 115px; -pie-png-fix:true;behavior:url(http://qm-static.s3-website-us-west-2.amazonaws.com/js/PIE.htc);}    290 .bill-form .txt.month {width: 40px!important; margin-right: 10px; margin-left: 96px;}
        .bill-form .sel.month .customSelect{width:120px; background:url(http://qm-static.s3-website-us-west-2.amazonaws.com/images/sel-ico.png) no-repeat 115px; -pie-png-fix:true;behavior:url(http://qm-static.s3-website-us-west-2.amazonaws.com/js/PIE.htc);}
        .bill-form .sel.day .customSelect{width:80px; background:url(http://qm-static.s3-website-us-west-2.amazonaws.com/images/sel-ico.png) no-repeat 75px; -pie-png-fix:true;behavior:url(http://qm-static.s3-website-us-west-2.amazonaws.com/js/PIE.htc);}

        /*314*/
        .boxedinn.addons{height:110px;}
        .mail-market .line{margin-top:15px !important;}

         /* add 08-20-13*/
        .boxedinn.addons.autoheight{height:auto !important;}
        .ct6 .bxd .monitoring {float:left; text-align:left; display:inline-block;padding:8px 0 0 70px; height:40px; font-size:18px; color:#666666; background:url(http://qm-static.s3-website-us-west-2.amazonaws.com/images/24-7-ico.png) no-repeat; width:650px;}
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
                            <div class="ct-dtl clearfix">
                                <h1>Local Online Marketing Simplified </h1>
                                <p>Quality Media is committed to seeing your company succeed. Every customer receives a full-time dedicated<br /> account manager who will oversee the success of your marketing campaigns. </p>
                            </div>
                        </div>
                    </div>
                </div>
                <form id="ProductsForm" action="<?php echo $this->createUrl('/products/order'); ?>" method="post">
                <div class="section ct6">
                    <div class="container2">
                        <div class="inner-ct">
                            <h2 class="heading">BUILD YOUR BUSINESS WITH SOCIAL MEDIA MARKETING</h2>
                            <div class="ct-dtl clearfix">
                                <div class="bxd clearfix">
                                    <div class="plan-wrap clearfix">
                                        <div class="sdleft">
                                            <div class="step clearfix"><span class="point">1</span> <span class="caption brk"><strong>Select<br> your plan</strong></span></div>
                                            <div class="socplan-box">
                                                <span class="socplan yelp">Yelp</span>
                                                <span class="socplan gplus">Google +</span>
                                                <span class="socplan fb">Facebook</span>
                                                <span class="socplan tw">Twitter</span>
                                            </div>
                                        </div>
                                        <div class="list">
                                            <div class="item <?php echo $plans['basic']->planCode; ?>">
                                                <div class="radio"><input type="radio" id="<?php echo $plans['basic']->planCode; ?>" value="<?php echo $plans['basic']->planCode; ?>" name="ProductsForm[plan]" /> <label for="<?php echo $plans['basic']->planCode; ?>"><?php echo $plans['basic']->name; ?></label></div>
                                            </div>
                                            <div class="item <?php echo $plans['basicplus']->planCode; ?>">
                                                <div class="radio"><input checked="checked" type="radio" id="<?php echo $plans['basicplus']->planCode; ?>" value="<?php echo $plans['basicplus']->planCode; ?>" name="ProductsForm[plan]" /> <label for="<?php echo $plans['basicplus']->planCode; ?>"><?php echo $plans['basicplus']->name; ?></label></div>
                                            </div>
                                            <div class="item <?php echo $plans['value']->planCode; ?>">
                                                <div class="radio sel"><input checked="checked" type="radio" id="<?php echo $plans['value']->planCode; ?>" value="<?php echo $plans['value']->planCode; ?>" name="ProductsForm[plan]" /> <label for="<?php echo $plans['value']->planCode; ?>"><?php echo $plans['value']->name; ?></label></div>
                                            </div>
                                            <div class="item <?php echo $plans['premium']->planCode; ?>">
                                                <div class="radio"><input type="radio" id="<?php echo $plans['premium']->planCode; ?>" value="<?php echo $plans['premium']->planCode; ?>" name="ProductsForm[plan]" /> <label for="<?php echo $plans['premium']->planCode; ?>"><?php echo $plans['premium']->name; ?></label></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bxd clearfix">
                                    <div class="inn  clearfix">
                                        <div class="step clearfix"><span class="point">2</span> <span class="caption"><strong>Select your add-ons</strong> (optional)</span></div>
                                        <p class="space-2"></p>
                                        <div class="boxedinn addons clearfix">
                                            <span class="mail-market">
                                                <strong>Email Marketing: $40-100/month</strong>
                                                <div class="line"></div>
                                                <p class="sel">
                                                    <input type="hidden" value="" disabled="disabled" id="ProductsFormAddonsEmailMarketing" name="ProductsForm[addons][]" />
                                                    <select id="addonsEmailMarketingSelect">
                                                        <option value="">Select an option</option>
                                                        <option value="email-marketing-0">Up to 1,000 emails - $40 / month</option>
                                                        <option value="email-marketing-1">1,000 - 2,000 emails - $75 / month</option>
                                                        <option value="email-marketing-2">2,000 + emails - $100 / month</option>
                                                    </select>
                                                </p>
                                            </span>
                                            <a href="#ProductsFormAddonsEmailMarketing" class="right add-plan">Add to plan</a>
                                            <div id="noplan-error" style="display: none">Please pick an email campaign plan</div>
                                        </div>

                                        <p class="space-2"></p>
                                        <div class="boxedinn addons clearfix">
                                            <span class="socialstar">
                                                <strong>Social Star: $100/month</strong><br />
                                                <input disabled="disabled" id="ProductsFormAddonsSocialstar" name="ProductsForm[addons][]" value="socialstar" type="hidden" />
                                                <small>Looking to be a social media rock star? Have our team post 5 engaging posts/week<br /> instead of 3 per SM site</small>
                                            </span>
                                            <a href="#ProductsFormAddonsSocialstar" class="right add-plan btn-selected">Add to plan</a>
                                        </div>

                                        <p class="space-2"></p>
                                        <div class="boxedinn addons autoheight clearfix">
                                            <span class="tripadv">
                                                <strong>Trip Advisor: $100/month</strong>
                                                <input disabled="disabled" id="ProductsFormAddonsTripadvisor" name="ProductsForm[addons][]" value="tripadvisor" type="hidden" />
                                            </span>
                                            <a href="#ProductsFormAddonsTripadvisor" class="right add-plan btn-selected">Add to plan</a>
                                        </div>

                                        <p class="space-2"></p>
                                        <div class="boxedinn addons autoheight clearfix">
                                            <span class="foursq">
                                                <strong>Foursquare: $100/month</strong>
                                                <input disabled="disabled" id="ProductsFormAddonsFoursquare" name="ProductsForm[addons][]" value="foursquare" type="hidden" />
                                            </span>
                                            <a href="#ProductsFormAddonsFoursquare" class="right add-plan btn-selected">Add to plan</a>
                                        </div>
                                       <?php /* Temporary disabled
                                       <p class="space-2"></p>
                                        <div class="boxedinn addons autoheight clearfix">
                                            <span class="monitoring">
                                                <strong>24/7 Monitoring</strong>
                                                <input disabled="disabled" id="ProductsFormAddonsMonitoring247" name="ProductsForm[addons][]" value="monitoring-24-7" type="hidden" />
                                            </span>
                                            <a href="#ProductsFormAddonsMonitoring247" class="right add-plan">Add to plan</a>
                                        </div> */ ?>
                                    </div>
                                </div>

                                <div class="bxd clearfix bot">
                                    <div class="inn  clearfix">
                                        <div class="step"><span class="point">3</span> <span class="caption">Your dedicated account manager works to see the success of your campaigns</span></div>
                                        <input type="submit" class="left signup" value="Sign-up now" style="position: absolute; top: 46px; right: 70px; margin: 0; border: none; text-indent: -2000px; height: 51px; width: 210px; background: url(<?php echo $this->resourceUrl('/images/btn-signup-now.png', 's3'); ?>)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <div class="section ct3">
                    <div class="container">
                        <div class="inner-ct">
                            <h2 class="heading" id="our-customer">A Word From Our Clients</h2>
                            <div class="ct-dtl clearfix">
                                <div class="col3">
                                    <div class="col-ct">
                                        <div class="quote">
                                            <p>Quality Media has helped manage our review sites which gives us time to focus on the core of our business. Since they have taken over, we have seen a HUGE increase in traffic and positive reviews. The investment has already paid tenfold!</p>
                                        </div>
                                        <div class="sender">
                                            <?php echo CHtml::image($this->resourceUrl('images/img-quote1.png', 's3')); ?>
                                            <p>Peddy, PIzza Rustica<span>West Hollywood, CA</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col3">
                                    <div class="col-ct">
                                        <div class="quote">
                                            <p>You guys have engaged with all of our online reviews and helped increase the frequency of visits because of it! Thanks for managing my account with such care, you guys have done terrific Job!</p>
                                        </div>
                                        <div class="sender">
                                            <?php echo CHtml::image($this->resourceUrl('images/img-quote2.png', 's3')); ?>
                                            <p>Cedric T., Sofic Greek Resto<span>Lost Angeles, CA</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col3">
                                    <div class="col-ct">
                                        <div class="quote">
                                            <p>Quality Media has helped me realize how important my online reviews are.  My account rep has reached out to all of our reviews, optimized my account, and gotten us more business! This has been an A+ experience so far, thanks!</p>
                                        </div>
                                        <div class="sender">
                                            <?php echo CHtml::image($this->resourceUrl('images/img-quote3.png', 's3')); ?>
                                            <p>Tom D., West Coast Tires<span>Los Angeles, CA</span></p>
                                        </div>
                                    </div>
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