    <?php
    /**
     * System Notifications
     */
        $this->beginWidget('bootstrap.widgets.TbModal', array(
            'id' => 'systemNotificationsModal',
            'options' => array('show' => false),
            'htmlOptions'=>array('style'=> 'margin-left: -400px; width: 800px')
        ));
    ?>

        <div class="modal-header" style="padding: 20px 15px;">
            <a class="close" data-dismiss="modal">&times;</a>
            <h4>System Notifications:</h4>

            <div style="height: 250px; overflow-y: auto" id="systemNotificationsContent">

            &nbsp;
            </div>
        </div>

    <?php
        $this->endWidget();

        Yii::app()->clientScript->registerScript('systemNotifications', "

            $('.notification-archive').live( 'click',function(e) {
                e.preventDefault();

                $.post('".$this->createUrl('/am/dashboard/systemNotifications')."', {action: 'markAsArchived', id: $(this).attr('rel')}, function(data) {
                    $('#systemNotificationsContent').html(data);
                });
            });

            $('#systemNotificationsModal').on('show', function () {
                $.get('".$this->createUrl('/am/dashboard/systemNotifications')."', function(data) {
                    $('#systemNotificationsContent').html(data);

                    $.get('".$this->createUrl('/am/dashboard/getUnreadNotifications')."', function(data) {
                        var response = $.parseJSON(data);
                        $('#notifications-counter').html(response.notifications);
                    });

                });
            });
        ");
    ?>