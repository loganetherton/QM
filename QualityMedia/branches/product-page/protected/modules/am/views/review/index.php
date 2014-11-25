<?php
    $this->setPageTitle('Manage reviews');
    $this->renderPartial('/layouts/tabs/review', array('active'=>$this->currentTab));
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
        <?php
            $this->widget('ReviewListView', array(
                'id'=>'reviews-list',
                'dataProvider'=>$model->search(),
                'itemView'=>'_review',
                'viewData'=>array(
                    'formatter'=>Yii::app()->getComponent('format'),
                ),
                'headers'=>array('Site', 'Client', 'Date', 'Last Action', 'Reviewer', 'Rating', ''),
                'afterAjaxUpdate'=>'js:function(id, data){ reviewGrid(); }',
            ));

            $starOff    = $this->resourceUrl('images/star-off.png', 's3');
            $starOn     = $this->resourceUrl('images/star-on.png', 's3');

            Yii::app()->getClientScript()->registerScript('review-fbhsj', "
                function reviewGrid()
                {
                    jQuery('.tb-child').hide();

                    jQuery('.expand a').click(function(e) {
                        e.preventDefault();

                        var reviewRow = jQuery(this).parent().parent();

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
                }

                reviewGrid();
            ");

            //Flag Prompt alert
            Yii::app()->getClientScript()->registerScript('flagPromptScript', "
                $('.flagPromptBtn').live('click', function(e) {
                    e.preventDefault();

                    var btnSubmit = $(this);
                    $('#flagPrompt .alert-error').hide();
                    $('#flagPrompt').modal('show');

                    $('#flagPrompt .btn-flagApply').click(function(e) {
                        e.preventDefault();
                        var flagReason = $('#flagPrompt .review-flagReason');

                        if(flagReason.val() !== '') {
                            btnSubmit.parent().children('.review-flagReason').val(flagReason.val());
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
        ?>
    </div>
    <div id="ch-sidebar" class="span3">
        <?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>
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
            <br />
            <textarea class="review-flagReason" style="width: 98%; margin: auto; margin-bottom: 10px"></textarea>
        </div>

        <div class="alert alert-error" style="display: none">You must provide a reason</div>
    </div>

    <div class="modal-footer">
        <a href="#" class="btn btn-flagCancel" data-dismiss="modal" aria-hidden="true">Cancel</a>
        <a href="#" class="btn btn-primary btn-flagApply">Flag Review</a>
    </div>
</div>