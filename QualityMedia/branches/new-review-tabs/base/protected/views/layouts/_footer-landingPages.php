<?php
$defaultDomain = Yii::app()->params['domains']['default'];
$signupDomain = Yii::app()->params['domains']['signup']
?>
<div id="footer">

<div class="container clearfix">
    <div class="foot-inner clearfix">
        <div class="foot-nav pull-left">
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, ''); ?>">HOME</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#what-we-do'); ?>">WHAT WE DO</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#why-we-do-it'); ?>">WHY WE DO IT</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#our-customers'); ?>">OUR CUSTOMERS</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, 'products'); ?>">PRODUCTS</a>
                <a href="<?php echo sprintf('%s/%s', $signupDomain, ''); ?>">PRICING</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, 'refunds'); ?>">REFUND POLICY</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, 'privacy'); ?>">PRIVACY POLICY</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#featured-on'); ?>">FEATURED ON</a>
                <a href="<?php echo sprintf('%s/%s', $defaultDomain, '#about-us'); ?>">ABOUT</a>
                <a href="mailto:helpdesk@qualitymedia.com">CONTACT US</a>
        </div>
        <div class="credit pull-right">© 2013 QUALITYMEDIA</div>
    </div>
</div>
</div>

<!-- popup begin -->
<div id="thankyou-popup" style="display:none;">
    <div class="pop-overlay"></div>
    <div class="popup-wrap">
        <div class="popup-box">
            <div class="section">
                <h3 class="heading" style="margin-top: 10px">THANK YOU!</h3>
                <center>
                    <h4 style="margin-top: 20px; margin-bottom: 30px">A member of our team will contact you shortly.</h4>
                    <a href="<?php echo sprintf('%s/%s', $defaultDomain, ''); ?>" class="btn-bigblue">Close</a>
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

        function isValidPhoneNumber(phone)
        {
            var pattern = new RegExp(/^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$/);
            return pattern.test(phone);
        }

        $('#submitForm').click(function(){
            var hasError = false;

            $('#contactform input[placeholder], textarea[placeholder]').placeholder();

            if($('#contactform #email').length) {
                var emailVal = $('#contactform #email').val();
            }

            var nameVal = $('#contactform #name').val();
            var businessNameVal = $('#contactform #businessName').val();
            var messageVal = $('#contactform #message').val();

            if($('#contactform #phone').length) {
                var phoneVal = $('#contactform #phone').val();
            }

            $('#contactform input[placeholder], textarea[placeholder]').placeholder();

            if($('#contactform #email').length) {
                if(emailVal == '' || emailVal == $('#contactform #email').attr('placeholder')) {
                    alert('You forgot to enter the email address.');
                    hasError = true;
                } else if(!isValidEmailAddress(emailVal)) {
                    alert('Enter a valid email address to send to.');
                    hasError = true;
                }
            }

            if(nameVal == '' || nameVal == $('#contactform #name').attr('placeholder')) {
                alert('You forgot to enter your name.');
                hasError = true;
            }

            if(businessNameVal == '' || businessNameVal == $('#contactform #businessName').attr('placeholder')) {
                alert('You forgot to enter your company name.');
                hasError = true;
            }

            if($('#contactform #phone').length) {
                if(phoneVal == '' || phoneVal == $('#contactform #phone').attr('placeholder')) {
                    alert('You forgot to enter the phone number');
                    hasError = true;
                }
                else if(!isValidPhoneNumber(phoneVal)) {
                    alert('Enter a valid phone number');
                    hasError = true;
                }
            }

            if(hasError == false) {
                var formData = { ref:'contact', email: emailVal, name: nameVal, businessName: businessNameVal, message: messageVal, phone: phoneVal };

                var formSource = $('#formSource');
                if(formSource.length) {
                    formData.source = formSource.val();
                }

                $.post('<?php echo $this->createUrl('/contact'); ?>', formData,function(data){
                    $('#thankyou-popup').show();

                    // Google tracking conversion
                    var google_conversion_id = 995444632;
                    var google_conversion_language = "en";
                    var google_conversion_format = "2";
                    var google_conversion_color = "ffffff";
                    var google_conversion_label = "MPVvCIDZ9QQQmI_V2gM";
                    var google_conversion_value = 0;

                    $.getScript('https://www.googleadservices.com/pagead/conversion.js');
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
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-46053941-1', 'qualitymedia.com');
      ga('send', 'pageview');

    </script>