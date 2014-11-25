<?php
    $this->setPageTitle('Private Messages');
    $this->renderPartial('/layouts/tabs/messageJr', array('active'=>$this->currentTab, 'id' => $accountManager->id));

    $dataProvider = $model->searchMessages();
    $dataProvider->pagination->pageSize = 20;

    $headers = array('Approval', 'Site', 'Client', 'Date', 'Last Action', 'Reviewer', 'Rating', '');

    //Add approval status icons if the user is Jr AM
    if(!Yii::app()->getUser()->isSenior()) {
        array_unshift($headers, 'Approval');
    }

    $this->leftContent = sprintf('<div style="font-weight: bold; font-size: 12px" class="alert alert-success">Reviewing %s %s\'s accounts</div>', $accountManager->firstName, $accountManager->lastName);

?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span9">
        <?php
            $this->widget('ReviewListView', array(
                'id'=>'messages-list',
                'dataProvider'=>$dataProvider,
                'itemView'=>'_messageJr',
                'viewData'=>array(
                    'formatter'=>Yii::app()->getComponent('format'),
                ),
                'headers'=> $headers,
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

        ?>
    </div>
    <div id="sd-bar" class="span3 accordion">
        <?php $this->renderPartial('/layouts/partials/_sidebar', array('model'=>$model, 'listViewId' => 'messages-list'));

        if(Yii::app()->getUser()->isSenior()) {
            $seniorAmModel = Yii::app()->getUser()->getUser();
            $juniorAms = $seniorAmModel->getLinkedAccountManagers();

            $this->widget('JuniorAmsListView', array(
                'dataProvider' => $juniorAms,
                'itemView'     =>'/layouts/lists/_juniorAms',
                'viewData'     => array('linkSection' => '/am/message/jr/')
            ));
        } ?>
    </div>
</div>