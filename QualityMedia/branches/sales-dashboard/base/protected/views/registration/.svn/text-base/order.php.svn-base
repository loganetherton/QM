<?php
    $this->setPageTitle('Sign Up');

    // Recurly form goes here
    $recurlyApi = Yii::app()->getComponent('recurly');
    $successPage = $successPage = Yii::app()->params['domains']['signup'].'/registration/success';

    $subscriptionForm = array(
        'target'            => '#recurly-form',
        'planCode'          => isset($plan) ? $plan->planCode : $recurlyApi->planCode,
        'currency'          => $recurlyApi->currency,
        'enableCoupons'     => false,
        'collectCompany'    => true,
        'termsOfServiceURL' => '#agreement',
        'successHandler'    => "js:function(token){ window.location='{$successPage}'; }",
        'afterInject'       =>'js:function(formEl){ recurlyFormInit(formEl); }',
        'account'           => ''
    );

    $subscriptionFormConfig = array(
        'subdomain'=>$recurlyApi->subdomain,
        'currency'=>$recurlyApi->currency,
        'locale'=>array(
            'errors'=>array(
                'acceptTOS'=>'Please accept the agreement',
            ),
        ),
    );

    $subscriptionFormSettings = array(
        'signature'=> Recurly_js::sign(array()),
        'currency'=>'USD',
    );

    $subscriptionFormFuncData = array_merge($subscriptionFormSettings, $subscriptionFormConfig, $subscriptionForm);

    $addonsInfo = array(
        'email-marketing-0' => 'Up to 1,000 emails',
        'email-marketing-1' => '1,000 - 2,000 emails',
        'email-marketing-2' => '2,000 + emails',
        'monitoring-24-7' => 'Unlimited Customer Support',
    );

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

        <style type="text/css">
            .add_ons .add_on {position: relative;}
            .add_ons .add_on .addonRemove {position: absolute; top: 0; right: 0;}
            .error {font-weight: bold; color: #ff0000;}
        </style>

        <script type="text/javascript" src="<?php echo $this->resourceUrl('js/jquery.numeric.js', 's3'); ?>" charset="utf-8"></script>
        <script type="text/javascript">

            Array.prototype.remove = function() {
                var what, a = arguments, L = a.length, ax;
                while (L && this.length) {
                    what = a[--L];
                    while ((ax = this.indexOf(what)) !== -1) {
                        this.splice(ax, 1);
                    }
                }
                return this;
            };

            var selectedPlan = '<?php echo $plan->planCode; ?>';
            var selectedAddons = [];
            var formData = {'billingInfo' : {}, account: {}};

            <?php
            //add addons if choosen
            if($addons && is_array($addons)) {
                foreach($addons as $addon): ?>
                selectedAddons.push('<?php echo $addon; ?>');
            <?php
                endforeach;
            } ?>

            //On recurly form init
            var toggleAddon = function(addonCode) {
                $('.add_on_'+ addonCode).click();
            };

            //add customized info to the addon
            var addAddonInfo = function(addonCode, info) {
                var infoContent = $('<div>'+info+'</div>');
                $('#add_on_'+ addonCode).find('.infolist').append(infoContent);
            }

            var recurlyFormInit = function(formEl) {

                // $('p.sel select', formEl).customSelect();
                $('p.sel .customSelectInner', formEl).css('width', 'auto');


                //numeric input restriction
                $('.numeric').numeric();

                <?php
                //add addons if choosen
                foreach($addonsInfo as $addonCode => $addonInfo): ?>
                    addAddonInfo('<?php echo $addonCode; ?>', '<?php echo $addonInfo; ?>');
                <?php endforeach; ?>

                $(selectedAddons).each(function(index) {
                    var addon = selectedAddons[index];

                    if(addon != '' && addon !== undefined) {
                        toggleAddon(addon);
                    }
                });

                $('.add_on').hide();
                $('.add_on.selected').show();
                $('.add_ons').undelegate('.add_on', 'click');

                $('.add_on .addonRemove a').click(function(e) {
                    e.preventDefault();
                    var addonObj = $(this).parent().parent();
                    var addonId = addonObj.attr('id').substr(7);
                    addonObj.hide();
                    selectedAddons.remove(addonId);
                });

                //create plan select
                var plans = <?php echo CJSON::encode($model->plansDropDownList()); ?>

                var planSelect = $('.selectedPlan select');

                planSelect.find('option').remove();
                $.each(plans, function(key, value) {
                     planSelect
                          .append($('<option>', { value : key })
                          .text(value));
                });
                planSelect.val(selectedPlan);
                planSelect.change(function() {
                    selectedPlan = $(this).val()
                    reloadSubscriptionForm();
                });

                populateForm();
                $('input[placeholder], textarea[placeholder]').placeholder();
            };

            var reloadSubscriptionForm = function() {
                var formParams = <?php echo CJavaScript::encode($subscriptionFormFuncData); ?>;
                formParams.planCode = selectedPlan;

                //populate form
                loadFormData();

                formParams.BillingInfo = formData.billingInfo;
                formParams.Account = formData.account;

                Recurly.buildSubscriptionForm(formParams);
            }

            var loadFormData = function() {

                for(var itemKey in preFillMap.billingInfo) {
                    var obj = $(preFillMap.billingInfo[itemKey]).first();
                    formData.billingInfo[itemKey] = obj.val();
                }

                for(var itemKey in preFillMap.account) {
                    var obj = $(preFillMap.account[itemKey]).first();
                    formData.account[itemKey] = obj.val();
                }
            }

            var populateForm = function() {

                for(var itemKey in preFillMap.billingInfo) {
                    var obj = $(preFillMap.billingInfo[itemKey]).first();
                    obj.val(formData.billingInfo[itemKey]);
                }

                for(var itemKey in preFillMap.account) {
                    var obj = $(preFillMap.account[itemKey]).first();
                    // obj.val(formData.account[itemKey]);
                    if(typeof(obj) == 'object') {
                        obj.val(formData.account[itemKey]);
                    }
                }
            }

        </script>
    </head>

    <body>
        <!-- Wrap all page content here -->
        <div id="wrap">
            <!-- Fixed navbar -->
            <?php $this->renderPartial('/layouts/_header'); ?>
            <!-- Begin page content -->
            <div id="content">
                <div class="container" id="recurly-form">
                    <!-- recurly form goes here -->
                    <?php
                        $this->widget('ext.recurly.RecurlyWidget', array(
                            'config'=> $subscriptionFormConfig,
                            'recurlyScript' => 'recurly-billing-form.min.js',
                            'subscriptionForm'=> $subscriptionForm
                        ));
                    ?>
                </div>
            </div>
        </div>

        <?php $this->renderPartial('/layouts/_footer'); ?>

      <!-- Modal -->
      <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsLabel" aria-hidden="true">
        <div class="modal-dialog" style="width: 700px;">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title"><strong>QualityMedia</strong> Terms and Conditions for Products and Services</h4>
            </div>
            <div class="modal-body" style="height: 350px; overflow: auto; font-size: 11px; text-align: justify">
                    <h3 style="font-size: 12px">THESE TERMS AND CONDITIONS (THE "TERMS" OR "TERMS OF USE") GOVERN YOUR ACCESS TO AND USE OF QUALITY MEDIA'S WEBSITES AND YOUR PURCHASES OF PRODUCTS AND SERVICES FROM QUALITYMEDIA, INC. ("QUALITY MEDIA", THE "COMPANY", "WE", OR "OUR").THIS AGREEMENT DEFINES THE RELATIONSHIP BETWEEN QUALITY MEDIA INC. AND YOU ("YOU", "YOUR", THE "CLIENT"). IF YOU ARE ENTERING INTO THIS AGREEMENT ON BEHALF OF A COMPANY OR OTHER LEGAL ENTITY, YOU ALSO REPRESENT THAT YOU HAVE THE AUTHORITY TO BIND SUCH ENTITY TO THESE TERMS, IN WHICH CASE THE TERMS "YOU", "YOUR" OR "CLIENT" SHALL REFER TO SUCH ENTITY.</h3>
                    <br />
                    <p><strong>SERVICES.</strong></p>
                    <p><strong>1:1 Description.</strong> We provide online review management and privacy related products and services ("Services") for you or someone that you have designated to be the subject of the Services and for whom you will be held strictly responsible (the "Named Party").</p>
                    <br />
                    <p><strong>1:2 Online Reviews and Ratings.</strong> We are monitoring and managing your online reviews or ratings as part of your Services, as such, you represent and warrant that: (a) you are authorized to provide us with any customer, patient, and user information that you provide to us in connection with such Services (the "Reviewer Information"), including any personally identifying information of those parties; (b) our possession and/or use of the Reviewer Information on your behalf in connection with the Services will not violate any contract, statute, or regulation; and (c) any content that you and/or your authorized representative(s) submit for publication on an online review or ratings website as a provider of goods or services will be true and accurate, are the original work of your authorship, and will only concern you and the goods and/or services that you provide.</p>
                    <p><strong>USE OF SITE AND SERVICES.</strong></p>
                    <p><strong>2:1 User Accounts and Passwords.</strong> Certain features or services offered on or through the Site may require you to open an account (including setting up a QualityMedia.com ID and/or password(s)). You are entirely responsible for maintaining the confidentiality of the information you hold for your account, including your login ID and password, and for any and all activity that occurs under your account as a result of your failing to keep this information secure and confidential. You agree to notify us immediately of any unauthorized use of your account or password, or any other breach of security.</p>
                    <br />
                    <p><strong>FEES AND PAYMENT FOR SERVICES.</strong></p>
                    <p>
                        <strong>3:1 Fees and Auto-Renewal.</strong>
                        You agree to pay all fees specified on your accepted Order(s).
                        You are responsible for providing complete and accurate billing and contact information to us and for notifying us of any changes to such information.
                        Except as otherwise specified herein or on an Order, all payment obligations are non-cancelable and all fees paid are non-refundable.
                        You understand and accept that, unless otherwise expressly stated on the applicable Order, our Services are subscriptions services that operate on an auto-renewal basis such that your credit card, debit card, electronic payment, or other method of payment ("Accounts") will be assessed the specified fees at regular intervals based on your subscription program (e.g. annually, quarterly, monthly). The fees for each renewal term will be equal to the fees for the immediately prior term, unless we notify you at least thirty (30) days prior to such renewal of a change to the fees. You represent and warrant that you have the legal rights to use the Accounts and hereby authorize us to charge your Accounts for all Services listed on the Order(s) for the initial subscription term and each renewal term. Such charges shall be made in advance, either annually or in accordance with any different billing frequency stated in the applicable Order. You may cancel this month to month service at anytime with 30 days notice to Quality Media.
                        After such period, Quality Media will no longer work on your account, and your card will not be billed.</p>
                    <p></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#accept_tos a').live('click', function(e){
                    $('#termsModal').modal('show');
                });
            });
        </script>
    </body>
</html>