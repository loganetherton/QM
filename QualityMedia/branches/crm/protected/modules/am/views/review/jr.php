<?php
    $this->setPageTitle('Manage reviews');
    $this->renderPartial('/layouts/tabs/reviewJr', array('active'=>$this->currentTab, 'id' => $accountManager->id));

    $dataProvider = $model->search();
    $dataProvider->pagination->pageSize = 20;
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
        <?php
            $this->widget('ReviewListView', array(
                'id'=>'reviews-list',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_reviewJr',
                'viewData'=>array(
                    'formatter'=>Yii::app()->getComponent('format'),
                ),
                'sortableAttributes' => array(
                    'approvalStatus'     => 'Approval',
                    'companyName'     => 'Client',
                    'starRating' => 'Rating',
                    'userName'   => 'Reviewer',
                    'lastActionAt'   => 'Last&nbsp;Action',
                    'reviewDate' => 'Date'
                ),
                'headers'=>array('Approval', 'Client', 'Date', 'Last&nbsp;Action', 'Reviewer', 'Rating', ''),
                'afterAjaxUpdate'=>'js:function(id, data){ reviewGrid(); }',
            ));

            $starOff    = $this->resourceUrl('images/star-off.png', 's3');
            $starOn     = $this->resourceUrl('images/star-on.png', 's3');

            Yii::app()->getClientScript()->registerScript('review-fbhsj', "
                function reviewGrid()
                {
                    jQuery('.tb-child').hide();

                    jQuery('.expand.show-review a').click(function(e) {
                        e.preventDefault();

                        var reviewRow = jQuery(this).parent().parent();
                        var isUnread = reviewRow.hasClass('bold');

                        reviewRow.removeClass('bold');

                        if(reviewRow.hasClass('on')) {
                            reviewRow.removeClass('on');
                            reviewRow.next().hide();
                            jQuery(this).children('.text').html('Expand');
                        }
                        else {
                            reviewRow.addClass('on');
                            reviewRow.next().show();
                            jQuery(this).children('.text').html('Close');

                            if (isUnread) {
                                reviewRow.find('td:nth-child(4)').text('Read');
                            }
                            var grid = jQuery('#reviews-list');

                            jQuery.ajax({
                                url: jQuery(this).attr('href'),
                                type: 'POST',
                                dataType: 'json',

                                beforeSend: function() {
                                    grid.addClass('list-view-loading');
                                },

                                complete: function() {
                                    grid.removeClass('list-view-loading');
                                },

                                success: function(response) {
                                    // Update counters
                                    jQuery('#reviews-counter').html(response.reviews);
                                    jQuery('#messages-counter').html(response.messages);
                                }
                            });
                        }
                    });

                    if(jQuery('.rating').length > 0) {
                        jQuery('.rating').raty({
                            width   : 80,
                            starOff : '{$starOff}',
                            starOn  : '{$starOn}',
                            readOnly: true,
                            score   : function() {
                                return $(this).attr('data-score');
                            }
                        });
                    }

                    if(window.location.hash != '') {
                        var e = jQuery(window.location.hash+' .expand a');

                        e.parent().parent().addClass('on');
                        e.parent().parent().next().show();
                        e.children('.text').html('Close');
                    }

                    $('.userInfo').popover({
                        'placement': 'right',
                        'html'     : true,
                        'trigger'  : 'hover',
                        'animation': false
                    });

                    $('.btn-commentAction').click(function(e) {
                        var targetObj = $($(this).attr('rel'));
                        var targetError = $(targetObj.attr('rel'));
                        if(targetObj.val() == '') {
                            e.preventDefault();
                            targetError.fadeIn();
                        }
                    });
                    $('textarea[id^=response]').keyup(function() {
                        var targetError = $($(this).attr('rel'));
                        targetError.fadeOut();
                    });
                }

                reviewGrid();
            ");

            //Flag Prompt alert
            Yii::app()->getClientScript()->registerScript('flagPromptScript', "

            var relId, relObjExists, flagReason, flagCategory, PromptflagReason, PromptflagCategory, btnSubmit;

            function initFlagFeature() {

                $('#flagPrompt .review-flagCategory').live('change', function() {
                    if($(this).val() == '') {
                        $('#flagPrompt .review-flagReason').hide();
                    }
                    else {
                        $('#flagPrompt .review-flagReason').show();
                    }
                });

                $('.flagPromptBtn').live('click', function(e) {
                    e.preventDefault();

                    relId = $(this).attr('rel');
                    relObjExists = (relId !== undefined);

                    btnSubmit = $(this);

                    $('#flagPrompt .alert-error').hide();
                    $('#flagPrompt').modal('show');

                    if(relObjExists) {
                        PromptflagReason = $('#flagPrompt .review-flagReason');
                        PromptflagCategory = $('#flagPrompt .review-flagCategory');

                        flagReason= $(relId+'-reason');
                        flagCategory= $(relId+'-reasonCategory');

                        PromptflagReason.val(flagReason.val());
                        PromptflagCategory.val(flagCategory.val());

                    }
                });

                $('#flagPrompt .btn-flagApply').live('click', function(e) {
                    e.preventDefault();
                    if(!relObjExists) {
                        flagReason = $('#flagPrompt .review-flagReason');
                        flagCategory = $('#flagPrompt .review-flagCategory');
                    }

                    if(flagReason.val() !== '' && flagCategory.val() !== '') {

                        if(relObjExists) {
                            flagReason.val(PromptflagReason.val());
                            flagCategory.val(PromptflagCategory.val());

                            btnSubmit.parent().children('.btnSubmit').css('opacity', 0.6).click();
                        }
                        else {
                            btnSubmit.parent().children('.review-flagReason').val(flagReason.val());
                            btnSubmit.parent().children('.review-flagCategory').val(flagCategory.val());
                            btnSubmit.parent().children('.flagSubmit').click();
                        }
                    }
                    else {
                        $('#flagPrompt .alert-error').fadeIn();
                    }
                });

                $('#flagPrompt .btn-flagCancel').click(function() {
                    $('#flagPrompt .review-flagReason').val('');
                });

            }

            $(document).ready(function() {
                initFlagFeature();
            });
            ", CClientScript::POS_HEAD);

            //Edit Public Reply
            Yii::app()->getClientScript()->registerScript('PublicReplyEdit', "
                    $('.btn-editPublicReply').live('click', function(e) {
                        var targetObj = $($(this).attr('rel'));
                        var actionsObj = $($(this).attr('rel')+'-actions');
                        targetObj.show();
                        actionsObj.hide();
                    });
                    $('.btn-cancelPublicReplyEdit').live('click', function(e) {
                        var targetObj = $($(this).attr('rel'));
                        var actionsObj = $($(this).attr('rel')+'-actions');
                        targetObj.hide();
                        actionsObj.show();
                    });
            ");

            //Edit Private Reply
            Yii::app()->getClientScript()->registerScript('PrivateReplyEdit', "
                    $('.btn-editPrivateReply').live('click', function(e) {
                        var targetObj  = $($(this).attr('rel'));
                        var actionsObj = $($(this).attr('rel')+'-actions');
                        targetObj.show();
                        actionsObj.hide();
                    });
                    $('.btn-cancelPrivateReplyEdit').live('click', function(e) {
                        var targetObj = $($(this).attr('rel'));
                        var actionsObj = $($(this).attr('rel')+'-actions');
                        targetObj.hide();
                        actionsObj.show();
                    });
            ");

            //Senior Am Note
            Yii::app()->getClientScript()->registerScript('SeniorAmNote', "
                    $('.btn-seniorAmNote').live('click', function(e) {
                        var targetObj  = $($(this).attr('rel'));
                        var actionsObj = $($(this).attr('rel')+'-actions');
                        targetObj.show();
                        actionsObj.hide();
                    });
                    $('.btn-cancelSeniorAmNote').live('click', function(e) {
                        var targetObj = $($(this).attr('rel'));
                        var actionsObj = $($(this).attr('rel')+'-actions');
                        targetObj.hide();
                        actionsObj.show();
                    });
            ");

            Yii::app()->getClientScript()->registerScript('review-srcheck', "
                var formAction;
                var activeSubmitButton;
                var blockForm = true;
                var confirmMessage = 'Are you sure you want to override?';
                var formObjId;

                $('form.submitCheck button[type=submit]').click(function(e) {
                    activeSubmitButton = $(this);
                    formAction = activeSubmitButton.attr('name').match(/Review\[action\]\[(.*)\]/)[1];
                    formObjId = activeSubmitButton.closest('.tb-child').data('id');
                    console.log(formObjId);
                });

                $('form.submitCheck').submit(function(e) {
                    if(blockForm) {
                        e.preventDefault();
                        $.ajax({
                            type: 'POST',
                            url: '".$this->createUrl('/am/reviewAnswer/ajaxCheckPermission')."',
                            data: {'action': formAction, 'id': formObjId},
                            success: function(response) {
                                response = JSON.parse(response);
                                if(response.status == 'error' && response.errorType != 'notAllowed') {
                                    if (confirm(response.msg)) {
                                        blockForm = false;
                                        activeSubmitButton.click();
                                    } else {
                                        // Do nothing!
                                    }
                                }
                                else {
                                    blockForm = false;
                                    activeSubmitButton.click();
                                }
                            },
                        });
                    }
                });
            ");
        ?>
    </div>
    <div id="ch-sidebar" class="span3">
        <?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>

        <?php if(Yii::app()->getUser()->isSenior()): ?>
            <p class="line-base"></p>

            <div style="margin: 10px">
                <?php
                    $this->widget(
                        'bootstrap.widgets.TbButton',
                        array(
                            'label' => 'Junior Activities',
                            'type' => 'warning',
                            'htmlOptions' => array(
                                'target' => '_blank',
                            ),
                            'url' => $this->createUrl('/am/juniorAmActivity'),
                            'icon' => 'icon-tasks icon-white'
                        )
                    );
                ?>
            </div>

            <p class="line-base"></p>

            <div style="margin: 10px; font-size: 12px">
                <h4>Linked Junior Am's</h4>
                <?php
                $seniorAmModel = Yii::app()->getUser()->getUser();
                $juniorAms = $seniorAmModel->getLinkedAccountManagers();

                $this->widget('zii.widgets.CListView', array(
                        'dataProvider' => $juniorAms,
                        'itemView'     =>'seniorAm/_juniorAms',
                        'itemsTagName' =>'ul',
                        'itemsCssClass' => 'nav',
                        'template' =>'{items}{pager}',
                        'emptyText' => 'No Junior Managers linked',
                        'pager' => array(
                            'header' => 'Go to page:<br />',
                            'firstPageLabel' => '<<',
                            'lastPageLabel' => '>>',
                            'prevPageLabel' => '<',
                            'nextPageLabel' => '>'
                        ),
                        'pagerCssClass' => 'pagination jrAmsPagination'
                    ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Flag modal window -->
<div id="flagPrompt" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Please add a comment (Required)</h3>
    </div>

    <div class="modal-body">
        <p>Are you sure you want to flag this review? This action cannot be undone.</p>
        <div>
            <select id="flagCategory" name="flagCategory" class="input-xxlarge review-flagCategory">
                <option value="2">It's a fake</option>
                <option value="3">It doesn't describe a personal consumer experience</option>
                <option value="4">It uses offensive language or contains a personal attack</option>
                <option value="5">It violates Yelp's privacy standards</option>
                <option value="6">It contains promotional material</option>
                <option value="7">It's for the wrong business</option>
            </select>
            <br />
            <textarea class="review-flagReason" style="width: 98%; margin: auto; height: 100px; margin-bottom: 10px"></textarea>
        </div>

        <div class="alert alert-error" style="display: none">You must provide a reason</div>
    </div>

    <div class="modal-footer">
        <a href="#" class="btn btn-flagCancel" data-dismiss="modal" aria-hidden="true">Cancel</a>
        <a href="#" class="btn btn-primary btn-flagApply">Approve & Execute</a>
    </div>
</div>

<?php
$this->renderPartial('/notes/modal_jscript');
foreach ($dataProvider->getData() as $review) {
    $this->renderPartial('/notes/modal', array('data' => $review, 'tab' => $this->currentTab, 'type' => Note::TYPE_REVIEW, 'page' => '?page=' . (isset($_GET['page']) ? $_GET['page'] : 1)));
}
?>