<?php
    $this->setPageTitle('Private Messages');
    $this->renderPartial('/layouts/tabs/message', array('active'=>$this->currentTab));

    $dataProvider = $model->searchMessages();
    $dataProvider->pagination->pageSize = 20;
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
        <?php
            $this->widget('ReviewListView', array(
                'id'=>'messages-list',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_message',
                'viewData'=>array(
                    'formatter'=>Yii::app()->getComponent('format'),
                ),
                'headers'=>array('Site', 'Client', 'Date', 'Last Action', 'Reviewer', 'Rating', ''),
                'afterAjaxUpdate'=>'js:function(id, data){ messagesGrid(); }',
            ));

            $starOff    = $this->resourceUrl('images/star-off.png', 's3');
            $starOn     = $this->resourceUrl('images/star-on.png', 's3');

            Yii::app()->getClientScript()->registerScript('review-fbhsj', "
                function messagesGrid()
                {
                    jQuery('.tb-child').hide();

                    jQuery('.expand a').click(function(e){
                        e.preventDefault();

                        var reviewRow = jQuery(this).parent().parent();

                        reviewRow.removeClass('bold');

                        if(reviewRow.hasClass('on')) {
                            reviewRow.removeClass('on');
                            reviewRow.next().hide();
                            jQuery(this).children('.text').html('Expand');
                        }
                        else{
                            reviewRow.addClass('on');
                            reviewRow.next().show();
                            jQuery(this).children('.text').html('Close');

                            var grid = jQuery('#messages-list');

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

                    if(window.location.hash != '') {
                        var e = jQuery(window.location.hash+' .expand a');

                        e.parent().parent().addClass('on');
                        e.parent().parent().next().show();
                        e.children('.text').html('Close');
                    }
                }

                messagesGrid();
            ");
        ?>
    </div>

    <div id="ch-sidebar" class="span3">
        <?php $this->renderPartial('_sidebar', array('model'=>$model)); ?>
    </div>
</div>