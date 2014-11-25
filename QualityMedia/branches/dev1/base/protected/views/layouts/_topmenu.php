<?php
$defaultDomain = Yii::app()->params['domains']['default'];
$signupDomain = Yii::app()->params['domains']['signup']
?>
<div class="main-nav collapse navbar-collapse clearfix">
    <div class="clearfix navbar-centered">
        <ul class="nav navbar-nav mainMenu">
            <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, ''); ?>">HOME</a></li>
            <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, '#what-we-do'); ?>">WHAT WE DO</a></li>
            <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, '#why-we-do-it'); ?>">WHY WE DO IT</a></li>
            <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, '#our-customers'); ?>">OUR CUSTOMERS</a></li>
            <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, 'products'); ?>">PRODUCTS</a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, 'products#facebook'); ?>"> Facebook</a></li>
                    <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, 'products#twitter'); ?>"> Twitter</a></li>
                    <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, 'products#googleplus'); ?>"> Google+</a></li>
                    <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, 'products#yelp'); ?>"> Yelp</a></li>
                    <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, 'products#emailcampaigns'); ?>"> Email Marketing</a></li>
                </ul>
            </li>

            <li><a href="<?php echo sprintf('%s/%s', $signupDomain, ''); ?>">PRICING</a></li>
            <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, '#featured-on'); ?>">FEATURED ON</a></li>
            <li><a href="<?php echo sprintf('%s/%s', $defaultDomain, '#about-us'); ?>">ABOUT US</a></li>
            <li><a href="#contact-us">CONTACT US</a></li>
        </ul>
        <div class="navbar-right">
            <div class="phone-top">
                <?php //<a class="moustache" target="_blank" href="http://us.movember.com/"><?php echo CHtml::image($this->resourceUrl('images_v2/moustache.png', 's3'), '', array('width'=>50)); ? ></a> ?>
                <div class="social">
                    <?php
                        $image = CHtml::image($this->resourceUrl('images_v2/ico-fb.png', 's3'));
                        echo ' '.CHtml::link($image, 'https://www.facebook.com/QualityMedia1', array('target'=>'_blank'));

                        $image = CHtml::image($this->resourceUrl('images_v2/ico-googleplus.png', 's3'));
                        echo ' '.CHtml::link($image, 'https://plus.google.com/102089529779650947718/posts', array('target'=>'_blank'));

                        $image = CHtml::image($this->resourceUrl('images_v2/ico-tw.png', 's3'));
                        echo ' '.CHtml::link($image, 'https://twitter.com/QualityMediainc', array('target'=>'_blank'));
                    ?>
                </div>
                <div class="phone">888-435-5518</div>
            </div>
        </div>
    </div>
</div><!--/.nav-collapse -->
<script type="text/javascript">

$(document).ready(function() {
    $('ul.mainMenu > li').hover(function() {
        $(this).find('ul').show();

        if($(this).find('ul').length) {
            $(this).addClass('active');
        }
    },
    function() {
        $(this).find('ul').hide();
        $(this).removeClass('active');
    });
});
</script>