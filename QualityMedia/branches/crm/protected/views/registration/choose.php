<?php $this->setPageTitle('Choose a Plan');

$plans = $model->getPlans();

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
        <script text="javascript">

            $(document).ready(function() {

                //Toggle available plan addons

                $.fn.toggleDisabled = function(){
                    return this.each(function(){
                        this.disabled = !this.disabled;
                    });
                };

                $('#addonsEmailMarketingSelect').change(function() {
                    $('#ProductsFormAddonsEmailMarketing').val($(this).val());

                    if($(this).val() == '') {
                        $('input[data-target=#ProductsFormAddonsEmailMarketing]').removeClass('btn-bigblue').addClass('btn-biggray');
                    }
                    else {
                        $('input[data-target=#ProductsFormAddonsEmailMarketing]').addClass('btn-bigblue').removeClass('btn-biggray');
                        $('#noplan-error').hide();
                    }
                });

                $('.add-plan').click(function(e) {
                    e.preventDefault();

                    var obj = $($(this).data('target'));

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
                            $(this).val('Add to plan');
                        }
                        else {
                            $(this).val('Remove Add-on');
                        }
                    }
                });
            });
        </script>
    </head>

    <body>
        <!-- Wrap all page content here -->
        <div id="wrap">
            <!-- Fixed navbar -->
            <?php $this->renderPartial('/layouts/_header'); ?>
            <!-- Begin page content -->

            <div id="content">
                <div class="container">
                    <form id="ProductsForm" action="<?php echo $this->createUrl('/order'); ?>" method="post">
                        <h3 class="heading fittop">LOCAL ONLINE MARKETING SIMPLIFIED</h3>
                        <div class="center">
                            <h4 class="inf">Quality Media is committed to seeing your company succeed. Every customer receives a full-time<br /> dedicated account manager who will oversee the success of your marketing campaigns.</h4>
                        </div>

                        <h3 class="heading numbered"><span class="numbering">1</span> SELECT A PLAN</h3>
                        <div class="row plans">
                            <div class="col-md-2 col-sm-2">
                                <ul class="medias">
                                    <li><?php echo CHtml::image($this->resourceUrl('images_v2/yelp-ico.png', 's3'), ''); ?> Yelp</li>
                                    <li><?php echo CHtml::image($this->resourceUrl('images_v2/gplus-ico.png', 's3'), ''); ?> Google+</li>
                                    <li><?php echo CHtml::image($this->resourceUrl('images_v2/fb-ico.png', 's3'), ''); ?> Facebook</li>
                                    <li class="last"><?php echo CHtml::image($this->resourceUrl('images_v2/tw-ico.png', 's3'), ''); ?> Twitter</li>
                                </ul>
                                <p><small class="clr-gray">*ALL PLANS INCLUDE A ONE TIME SETUP FEE OF $200</small></p>
                            </div>
                            <div class="plan-wrap col-md-10 col-sm-10" style="padding: 0">
                                <div class="list">
                                    <div class="item <?php echo $plans['basic']->planCode; ?>">
                                        <div class="radio"><input type="radio" value="<?php echo $plans['basic']->planCode; ?>" id="<?php echo $plans['basic']->planCode; ?>" name="ProductsForm[plan]" /> <label for="<?php echo $plans['basic']->planCode; ?>"><?php echo $plans['basic']->name; ?></label></div>
                                    </div>
                                    <div class="item <?php echo $plans['basicplus']->planCode; ?>">
                                        <div class="radio"><input type="radio" value="<?php echo $plans['basicplus']->planCode; ?>" id="<?php echo $plans['basicplus']->planCode; ?>" name="ProductsForm[plan]" /> <label for="<?php echo $plans['basicplus']->planCode; ?>"><?php echo $plans['basicplus']->name; ?></label></div>
                                    </div>
                                    <div class="item <?php echo $plans['value']->planCode; ?>">
                                        <div class="radio sel"><input checked="checked" type="radio" value="<?php echo $plans['value']->planCode; ?>" id="<?php echo $plans['value']->planCode; ?>" name="ProductsForm[plan]" /> <label for="<?php echo $plans['value']->planCode; ?>"><?php echo $plans['value']->name; ?></label></div>
                                    </div>
                                    <div class="item <?php echo $plans['premium']->planCode; ?>">
                                        <div class="radio"><input type="radio" value="<?php echo $plans['premium']->planCode; ?>" id="<?php echo $plans['premium']->planCode; ?>" name="ProductsForm[plan]" /> <label for="<?php echo $plans['premium']->planCode; ?>"><?php echo $plans['premium']->name; ?></label></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h3 class="heading numbered"><span class="numbering">2</span> PICK ADD-ONS <small>(OPTIONAL)</small></h3>
                        <div class="addons">
                            <div class="item">
                                <div class="row">
                                    <div class="col-md-9">
                                        <?php echo CHtml::image($this->resourceUrl('images_v2/email-ico.png', 's3'), ''); ?>
                                        <div class="item-txt">Email Marketing: $40-100/month</div>
                                        <div class="pull-right">
                                            <input type="hidden" value="" disabled="disabled" id="ProductsFormAddonsEmailMarketing" name="ProductsForm[addons][]" />
                                            <select class="form-control cust" id="addonsEmailMarketingSelect">
                                                <option value="">Select an option</option>
                                                <option value="email-marketing-0">Up to 1,000 emails - $40 / month</option>
                                                <option value="email-marketing-1">1,000 - 2,000 emails - $75 / month</option>
                                                <option value="email-marketing-2">2,000 + emails - $100 / month</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 pull-right"><input data-target="#ProductsFormAddonsEmailMarketing" type="button" class="btn-biggray add-plan" value="Add to Plan"/></div>
                                </div>
                                    <div id="noplan-error" style="color: #ff0000; font-weight: bold; margin-left: 7px; display: none; clear: both">Please pick an email campaign plan</div>
                            </div>
                            <div class="item">
                                <div class="row">
                                    <div class="col-md-9">
                                        <?php echo CHtml::image($this->resourceUrl('images_v2/star-ico.png', 's3'), ''); ?>
                                        <div class="item-txt">Social Star: $100/month <small class="clr-gray">Looking to be a social media rock star? Have our team post 5 engaging posts/week instead of 3 per SM site</small></div>
                                        <input disabled="disabled" id="ProductsFormAddonsSocialstar" name="ProductsForm[addons][]" value="socialstar" type="hidden" />
                                    </div>
                                    <div class="col-md-2 pull-right"><input data-target="#ProductsFormAddonsSocialstar" type="button" class="btn-bigblue add-plan" value="Add to Plan"/></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="row">
                                    <div class="col-md-9">
                                        <?php echo CHtml::image($this->resourceUrl('images_v2/advisor-ico.png', 's3'), ''); ?>
                                        <div class="item-txt">Trip Advisor: $100/month</div>
                                    </div>
                                    <input disabled="disabled" id="ProductsFormAddonsTripadvisor" name="ProductsForm[addons][]" value="tripadvisor" type="hidden" />
                                    <div class="col-md-2 pull-right"><input data-target="#ProductsFormAddonsTripadvisor" type="button" class="btn-bigblue add-plan" value="Add to Plan"/></div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="row">
                                    <div class="col-md-9">
                                        <?php echo CHtml::image($this->resourceUrl('images_v2/foursquare-ico.png', 's3'), ''); ?>
                                        <div class="item-txt">Foursquare: $100/month</div>
                                        <input disabled="disabled" id="ProductsFormAddonsFoursquare" name="ProductsForm[addons][]" value="foursquare" type="hidden" />
                                    </div>
                                    <div class="col-md-2 pull-right"><input data-target="#ProductsFormAddonsFoursquare" type="button" class="btn-bigblue add-plan" value="Add to Plan"/></div>
                                </div>
                            </div>
                        </div>

                        <h3 class="heading numbered"><span class="numbering">3</span> ALL SET!</h3>
                        <div class="center">
                            <h4 class="inf">Your dedicated account manager works to see the success of your campaigns</h4><br />
                            <p><input type="submit" class="btn-bigblue" value="SIGN UP NOW!" /></p>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <?php $this->renderPartial('/layouts/_footer'); ?>
    </body>
</html>