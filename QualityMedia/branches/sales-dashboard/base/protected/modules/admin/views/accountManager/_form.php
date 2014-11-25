<?php
// Get Linked clients
$linkedClients = $userModel->accountManagerScope($model->id)->search();

//Ajax users to link searching
    $clientsToLink = new CActiveDataProvider('User', array('data'=>array()));
if(isset($_GET['clientsToLink'])) {
   $clientsToLink = $searchModel->unlinkedScope()->search();
}
?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'horizontal',
)); ?>
<h2><i class="icon3-user"></i> <?php echo $legend; ?></h2>

    <div class="row-fluid">
        <div class="span3">
            <?php echo $form->textField($model,'email', array('placeholder'=>'Email')); ?><?php echo $form->error($model,'email'); ?>
            <?php echo $form->textField($model,'firstName', array('placeholder'=>'First Name')); ?><?php echo $form->error($model,'firstName'); ?>
            <?php echo $form->passwordField($model,'password', array('placeholder'=>'Password')); ?><?php echo $form->error($model,'password'); ?>
        </div>
        <div class="span3">
            <?php echo $form->textField($model,'username', array('placeholder'=>'Username')); ?><?php echo $form->error($model,'username'); ?>
            <?php echo $form->textField($model,'lastName', array('placeholder'=>'Last Name')); ?><?php echo $form->error($model,'lastName'); ?>
            <?php echo $form->passwordField($model,'verifyPassword', array('placeholder'=>'Repeat password')); ?><?php echo $form->error($model,'verifyPassword'); ?>
        </div>
    </div>
    <p class="line-base"></p>

    <h2><i class="icon3-external-link-sign"></i> Login State</h2>
    <label class="checkbox inline">
        <?php echo $form->checkBox($model,'state'); ?> Enabled
    </label>
    <p class="line-base"></p>

    <h2><i class="icon3-comment"></i> Reviews and Private Messaging Settings</h2>
    <label class="checkbox inline">
        <?php echo $form->checkBox($model,'showOnlyLinkedFeeds'); ?> Show only linked? (Unchecked: Show All feeds)
    </label>
    <p class="line-base"></p>

    <h2><i class="icon3-link"></i> Link a client to his Account Manager</h2>
    <div class="row-fluid">
        <input type="text" autocomplete="off" class="input-xxlarge pull-left" id="clientsToLinkInput" placeholder="Enter text for quicksearch..." >
        <button type="submit" id="linkSubmit" class="btn btn-info pull-left disabled"><i class="icon3-link"></i> Link</button>
    </div>
    <div class="row-fluid">
    <?php
    /**
     * Register a function to bulk clients linking to the Account Manager
     */
    Yii::app()->clientScript->registerScript('search', "
        var ajaxUpdateTimeout;
        var ajaxRequest;
        var linkSubmitBtn = $('#linkSubmit');

        $(document).ready(function() {
            $('#clientsToLink').addClass('hide');

            $('#clientsToLinkInput').keyup(function(){
                var formData = 'clientsToLink[fullName]='+$(this).val();

                    clearTimeout(ajaxUpdateTimeout);
                    ajaxUpdateTimeout = setTimeout(function() {

                    $.fn.yiiGridView.update('clientsToLink', {
                        data: formData,
                        complete: function(jqXHR, status) {

                            if($('#clientsToLink').hasClass('hide')) {
                                $('#clientsToLink').removeClass('hide');
                            }

                            linkSubmitBtn.addClass('disabled');
                        }
                    });
                }, 200);
            });

            $('#clientsToLink input[type=checkbox]').live('click', function() {
                var selectedCount = $('#clientsToLink input[type=checkbox]:checked').length;
                if(selectedCount) {
                    linkSubmitBtn.removeClass('disabled');
                }
                else {
                    linkSubmitBtn.addClass('disabled');
                }
            });

            linkSubmitBtn.click(function(e) {
                e.preventDefault();
                var selectedItems = $('#clientsToLink input[type=checkbox]:checked');
                var formData = selectedItems.serialize();
                var ids = $('#clientsToLink').yiiGridView('getChecked', 'UserToLinkIds');

                $.ajax({
                    type: 'post',
                    url: '".$this->createUrl('ajaxLinkClients')."',
                    data: {'ids':ids, 'accountManagerId': ".$model->id."},
                    dataType: 'json',
                    success: function(resp){
                        $.fn.yiiGridView.update('linkedClients');
                        $('#clientsToLink').addClass('hide');
                        $('#clientsToLinkInput').val('');
                    }
                });

            });

            $('.unlinkClient').live('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.get(url, function() {
                    $.fn.yiiGridView.update('linkedClients');
                    $('#clientsToLink').addClass('hide');
                });
            });
        });
    ");
    ?>

    <?php
     $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider'=> $clientsToLink,
        'type'=>'bordered',
        'id' => 'clientsToLink',
        'hideHeader' => true,
        'htmlOptions' => array('class' => 'span6'),
        'template'=>'{items}{pager}',
        'itemsCssClass' => 'oview',
        'columns'=>array(
            array(
                'name'  => 'fullName',
                'value' => '$data->getFullName(", ")." | ".$data->billingInfo->companyName',
            ),
            array(
                'id'=>'UserToLinkIds',
                'class'=>'CCheckBoxColumn',
                'selectableRows' => 20
            ),
        ),
        ));
    ?>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <h3>Just Linked</h3>
            <?php
             $this->widget('bootstrap.widgets.TbGridView', array(
                'dataProvider'=>$linkedClients,
                'type'=>'bordered',
                'id' => 'linkedClients',
                'hideHeader' => true,
                'template'=>'{items}{pager}',
                'itemsCssClass' => 'oview',
                'columns'=>array(
                    array(
                        'name'  => 'fullName',
                        'value' => '$data->getFullName(", ")." | ".$data->billingInfo->companyName',
                    ),
                    array(
                        'name'=>'enabled',
                        'type'=>'raw',
                        'value'=>'CHtml::link("Remove Link", array("ajaxUnlinkClient", "id"=>$data->id), array("class"=>"unlinkClient"));',
                    ),
                ),
            ));
            ?>
        </div>
    </div>
    <p class="line-base"></p>

<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'size'=>'large', 'type'=>'info', 'label'=>'Update Account Manager')); ?>

<?php $this->endWidget(); ?>