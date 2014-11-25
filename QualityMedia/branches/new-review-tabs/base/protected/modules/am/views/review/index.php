<?php
    $this->setPageTitle('Manage reviews');
    $this->renderPartial('/layouts/tabs/review', array('active'=>$this->currentTab));

    //Review List data provider customization
    $dataProvider = $model->search();
    $dataProvider->pagination->pageSize = 20;

    if($this->currentTab == 'opened') {
        $dataProvider->setSort(array(
            'defaultOrder'=>'t.reviewDate DESC, t.lastActionAt, t.id ASC',
        ));
    }

    //Review list
    $headers = array('Add&nbsp;Notes', 'Client', 'Contract&nbspDate', 'Review&nbsp;Date', 'Added&nbsp;On', 'Last&nbsp;Action', 'Reviewer', 'Rating', '');

    $sortableAttributes = array(
        ($this->currentTab == 'flagged' ? 'flagApprovalStatus' : 'approvalStatus') => 'Approval',
        'companyName'     => 'Client',
        'starRating' => 'Rating',
        'userName'   => 'Reviewer',
        'lastActionAt'   => 'Last&nbsp;Action',
        'reviewDate' => 'Review&nbsp;Date',
        'createdAt'   => 'Added&nbso;On',
    );

    //Add approval status icons if the user is Jr AM
    if(!Yii::app()->getUser()->isSenior()) {
        array_unshift($headers, 'Approval');
        array_unshift($sortableAttributes, 'approvalStatus');
    }
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
        <?php
            $this->widget('ReviewListView', array(
                'id'=>'reviews-list',
                'dataProvider' => $dataProvider,
                'itemView'     => '_review',
                'viewData'     => array(
                    'formatter' => Yii::app()->getComponent('format'),
                ),
                'sortableAttributes' => $sortableAttributes,
                'enableSorting'   => true,
                'headers'         => $headers,
                'afterAjaxUpdate' =>'js:function(id, data){ reviewGrid(); }',
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
                                reviewRow.find('td.last-action').text('Read');
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

                    var btnSubmit = $(this);
                    $('#flagPrompt .alert-error').hide();

                    var reason = btnSubmit.parent().children('.review-flagReason').val();
                    if (reason.length > 0) {
                        $('#flagPrompt .rejected').show();
                        $('#flagPrompt .reason').text(reason);
                    }
                    else {
                        $('#flagPrompt .rejected').hide();
                    }

                    $('#flagPrompt').modal('show');

                    $('#flagPrompt .btn-flagApply').click(function(e) {
                        e.preventDefault();
                        var flagReason = $('#flagPrompt .review-flagReason');
                        var flagCategory = $('#flagPrompt .review-flagCategory');

                        if(flagReason.val() !== '' && flagCategory.val() !== '') {
                            btnSubmit.parent().children('.review-flagReason').val(flagReason.val());
                            btnSubmit.parent().children('.review-flagCategory').val(flagCategory.val());
                            btnSubmit.parent().children('.flagSubmit').click();
                        }
                        else {
                            $('#flagPrompt .alert-error').fadeIn();
                        }
                    });
                });

                $('#flagPrompt .btn-flagCancel').click(function() {
                    $('#flagPrompt .review-flagReason').val('');
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
    <div id="sd-bar" class="span3 accordion">
        <?php $this->renderPartial('/layouts/partials/_sidebar', array('model'=>$model, 'listViewId' => 'reviews-list'));

        if(Yii::app()->getUser()->isSenior()) {
            $seniorAmModel = Yii::app()->getUser()->getUser();
            $juniorAms = $seniorAmModel->getLinkedAccountManagers();

            $this->widget('JuniorAmsListView', array(
                'dataProvider' => $juniorAms,
                'itemView'     =>'/layouts/lists/_juniorAms',
                'viewData'     => array('linkSection' => '/am/review/jr/')
            ));
        } ?>
    </div>
</div>

<!-- Flag modal window -->
<div id="flagPrompt" class="modal hide fade">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Please add a comment (Required)</h3>
    </div>

    <div class="modal-body">
        <div class="rejected alert alert-info">
            <strong><?php echo Yii::app()->getUser()->isSenior() ? 'The' : 'Your' ?> previous flag was reverted by a Senior AM, the message originally entered was:</strong>
            <p class="reason"></p>
        </div>
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
        <a href="#" class="btn btn-primary btn-flagApply">Flag Review</a>
    </div>
</div>

<?php
$this->renderPartial('/notes/modal_jscript');
foreach ($dataProvider->getData() as $review) {
    $this->renderPartial('/notes/modal', array('data' => $review, 'tab' => $this->currentTab, 'type' => Note::TYPE_REVIEW, 'page' => '?page=' . (isset($_GET['page']) ? $_GET['page'] : 1)));
}
?>