<?php
$defaultDomain = Yii::app()->params['domains']['default'];
$signupDomain = Yii::app()->params['domains']['signup']
?>
<div id="contactForm">
    <a name="contact-us"></a>
    <div class="container">
        <h3 class="heading">CONTACT US</h3>
        <a name="contact-us"></a>
        <div class="row">
            <div class="left col-md-4">
                <?php echo CHtml::image($this->resourceUrl('images_v2/logo.png', 's3'), ''); ?>
                <p class="subtitle">Connect with us</p>
                <p>
                <?php
                    $image = CHtml::image($this->resourceUrl('images_v2/ico-fb.png', 's3'));
                    echo ' '.CHtml::link($image, 'https://www.facebook.com/QualityMedia1', array('target'=>'_blank', 'class'=>'socialLink'));

                    $image = CHtml::image($this->resourceUrl('images_v2/ico-googleplus.png', 's3'));
                    echo ' '.CHtml::link($image, 'https://plus.google.com/102089529779650947718/posts', array('target'=>'_blank', 'class'=>'socialLink'));

                    $image = CHtml::image($this->resourceUrl('images_v2/ico-tw.png', 's3'));
                    echo ' '.CHtml::link($image, 'https://twitter.com/QualityMediainc', array('target'=>'_blank', 'class'=>'socialLink'));
                ?>
                </p>
                <p><?php echo CHtml::link('helpdesk@qualitymedia.com', 'mailto:helpdesk@qualitymedia.com', array('class' => 'mailto')); ?></p>
                <p>+1 888-435-5518</p>
            </div>
            <div class="right col-md-8">
            <div class="pull-left formBlock">
                <form action="/contact.html" id="contactform" method="POST">
                    <p class="txt"><input class="input-text" type="text" id="name" name="name" placeholder="Name"/></p>
                    <p class="txt"><input class="input-text" type="text" id="email" name="email" placeholder="Email"/></p>
                    <p class="txt"><textarea class="textarea" id="message" name="message" placeholder="Message"></textarea></p>
                    <input class="btn-bigblue" type="submit" id="submit" value="Send"/>
                </form>
            </div>
            <div class="menuBlock pull-left" style="display: inline-block; with: auto; margin: 0 auto">
                <strong>Company</strong>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#about-us'); ?>">About Us</a>
                <a href="#">Support</a>
                <a href="#">Knowledgebase</a>
                <a href="#">Legal</a>
            </div>
            </div>
        </div>
    </div>
</div>

<div id="footer">
    <div class="container">
        <div class="foot-inner clearfix">
            <div class="foot-nav pull-left">
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, ''); ?>">HOME</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#what-we-do'); ?>">WHAT WE DO</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#why-we-do-it'); ?>">WHY WE DO IT</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#our-customers'); ?>">OUR CUSTOMERS</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, 'products'); ?>">PRODUCTS</a>
                <a href="<?php echo sprintf('%s/%s', $signupDomain, ''); ?>">PRICING</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#featured-on'); ?>">FEATURED ON</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#about-us'); ?>">ABOUT</a>
                <a href="mailto:helpdesk@qualitymedia.com">CONTACT US</a>
            </div>
            <div class="credit pull-right">&copy; 2013 QUALITYMEDIA</div>
        </div>
    </div>
</div>

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

<p id="back-top"><a href="#tops"><?php echo CHtml::image($this->resourceUrl('images/scrolltop.png', 's3')); ?></a></p>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo $this->resourceUrl('bootstrap_v3/js/bootstrap.min.js', 's3'); ?>"></script>
<script src="<?php echo $this->resourceUrl('bootstrap_v3/js/holder.js', 's3'); ?>"></script>

<script type="text/javascript">
    $(function () {

        //Scroll to top script
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

        //Placeholder
        $('input[placeholder], textarea[placeholder]').placeholder();

        //Contact Form

        function isValidEmailAddress(emailAddress)
        {
            var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
            return pattern.test(emailAddress);
        }


        $('#contactform').submit(function(e){
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
                $.post('<?php echo $this->createUrl('/contact'); ?>',{ ref:'contact', email: emailVal, name: nameVal, message: messageVal },function(data){
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
        });

        $('.close-popup').click(function(){
            $('.pop-overlay').hide();
            $('.popup-wrap').hide();
            $('.popup-box').hide();
        });
    })
    </script>