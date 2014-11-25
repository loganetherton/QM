<?php
    $this->setPageTitle('Products');
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
    </head>

    <body>
        <!-- Wrap all page content here -->
        <div id="wrap">
            <!-- Fixed navbar -->
            <?php $this->renderPartial('/layouts/_header'); ?>
            <!-- Begin page content -->
            <div id="content">
                <div class="container">

                    <h3 class="heading fittop">QUALITY MEDIA SERVICES</h3>
                    <div class="center">
                        <h4 class="inf">Quality Media is committed to seeing your company succeed. Every customer receives a full-time<br /> dedicated account manager who will oversee the success of your marketing campaigns.</h4>
                        <div class="img2">
                            <?php echo CHtml::image($this->resourceUrl('images_v2/img-qmservice.jpg', 's3'), ''); ?>
                        </div>

                        <p class="slogan">GROW YOUR BUSINESS WITH QUALITY MEDIA!</p>
                    </div>

                    <a id="facebook" name="facebook"></a>
                    <h3 class="heading">BUILD YOUR NETWORK WITH FACEBOOK</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="col-ct">
                                <h4 class="cblue-fb">Engage with your loyal fans using Facebook.</h4>
                                <p>Quality Media helps you attract more customers and spread word of mouth marketing. Our social media experts focus on posting original and exciting content, which help build conversations with your audience.</p>
                                <ul class="feat-list">
                                    <li>Complete optimization of your business page</li>
                                    <li>Engagement with your audience through daily posts and updates.</li>
                                    <li>Continuous management of your Facebook presence.</li>
                                    <li>Attract more "likes" and fans.</li>
                                </ul>
                                <?php echo CHtml::link('GET STARTED', $signupDomain, array('class'=>'btn btn-default bblue-fb')); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                            <?php echo CHtml::image($this->resourceUrl('images_v2/fb-page.jpg', 's3'), ''); ?>
                            </div>
                        </div>
                    </div>

                    <a id="twitter" name="twitter"></a>
                    <h3 class="heading">GET NEW CUSTOMERS WITH TWITTER</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="col-ct">
                                <h4 class="ctw">Twitter is a great way to connect and find new customers!</h4>
                                <p>Twitter is a great tool to increase brand awareness and give people a chance to learn about your business. Twitter is a fantastic communication channel that can assist with increasing brand loyalty, getting customer feedback, and connecting with customers directly.</p>
                                <ul class="feat-list">
                                    <li>Complete optimization of your twitter page</li>
                                    <li>Increase fans and followers for your business</li>
                                    <li>Engagement with your audience through exciting posts</li>
                                    <li>Compose special offers</li>
                                </ul>
                                <?php echo CHtml::link('GET STARTED', $signupDomain, array('class'=>'btn btn-default btw')); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                            <?php echo CHtml::image($this->resourceUrl('images_v2/tw-page.jpg', 's3'), ''); ?>
                            </div>
                        </div>
                    </div>

                    <a id="googleplus" name="googleplus"></a>
                    <h3 class="heading">BUILD YOUR NETWORK WITH GOOGLE PLUS</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="col-ct">
                                <h4 class="cgp">Google Plus, a great tool for content marketing and building communities.</h4>
                                <p>Google Plus for businesses is a great way to increase traffic to your business across all Google products. Some of the many products that will be enhanced via google plus are Google searches, Google maps, and many more. In addition to enhancing traffic to your brand, it is also a great way to build communities around your product or service.</p>
                                <ul class="feat-list">
                                    <li>Complete optimization of your Google Plus business page (helping others find you).</li>
                                    <li>Engagement with your audience through daily posts and updates.</li>
                                    <li>Building communities around your brand.</li>
                                    <li>Attracting more followers to your business page.</li>
                                </ul>
                                <?php echo CHtml::link('GET STARTED', $signupDomain, array('class'=>'btn btn-default bgp')); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                            <?php echo CHtml::image($this->resourceUrl('images_v2/gp-page.jpg', 's3'), ''); ?>
                            </div>
                        </div>
                    </div>

                    <a id="yelp" name="yelp"></a>
                    <h3 class="heading">GET PROPER ONLINE REVIEW MANAGEMENT</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="col-ct">
                                <h4 class="cyp">Harness the Power of Yelp.</h4>
                                <p>Quality Media helps you attract more customers and spread word of mouth marketing. Our social media experts focus on posting original and exciting content, which help build conversations with your audience.</p>
                                <ul class="feat-list">
                                    <li>Complete optimization of your business page</li>
                                    <li>Engagement with your audience through daily posts and updates.</li>
                                    <li>Continuous management of your Facebook presence.</li>
                                    <li>Attract more "likes" and fans.</li>
                                </ul>
                                <?php echo CHtml::link('GET STARTED', $signupDomain, array('class'=>'btn btn-default byp')); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                            <?php echo CHtml::image($this->resourceUrl('images_v2/yp-page.jpg', 's3'), ''); ?>
                            </div>
                        </div>
                    </div>

                    <a id="emailcampaigns" name="emailcampaigns"></a>
                    <h3 class="heading">REACH OUT TO YOUR CUSTOMERS WITH EMAIL CAMPAIGNS</h3>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="col-ct">
                                <h4 class="cgm">Why is an Email Campaign so important?</h4>
                                <p>Email campaigns serve a multitude of purposes. From raising brand awareness, to enagging with your audience, to offering specific promotions, emails are great funnel of communication.</p>
                                <ul class="feat-list">
                                    <li>Build brand awareness.</li>
                                    <li>Increase frequency of visits and sales.</li>
                                    <li>Strengthen customer relationships.</li>
                                    <li>Remind your customer who you are.</li>
                                    <li>Lead generation.</li>
                                    <li>Measure the results</li>
                                </ul>
                                <?php echo CHtml::link('GET STARTED', $signupDomain, array('class'=>'btn btn-default bgm')); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="img">
                            <?php echo CHtml::image($this->resourceUrl('images_v2/em-page.jpg', 's3'), ''); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php $this->renderPartial('/layouts/_footer'); ?>
    </body>
</html>