<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'activitiesSearchForm',
        'type' => 'inline',
        'method' => 'get',
        'htmlOptions' => array('class' => 'well'),
    )
);

$seniorAmModel = Yii::app()->getUser()->getUser();
$seniorAmsList = $seniorAmModel->linkedAmsDropDownList('fullName');
?>
<div class="pull-left w130">
    <?php
        echo $form->dropDownListRow(
            $model, 'accountManagerId', $seniorAmsList
        );
    ?>
</div>
<div class="pull-right">
    <?php
        echo $form->dateRangeRow(
            $model,
            'dateRange',
            array(
                'prepend' => '<i class="icon-calendar"></i>',
                'options' => array(
                    'format' => 'dd-MM-yyyy',
                    'callback' => 'js:function(start, end){console.log(start.toString("MMMM d, yyyy") + " - " + end.toString("MMMM d, yyyy"));}'
                ),
            )
        );
    ?>
</div>
<div class="clearfix"></div>
<div class="pull-left w130">
    <?php
        //If the Jr Am id is not specified, get the list from the first Jr Am in the list
        if(!$id) {
            $seniorAmsIds = array_keys($seniorAmsList);
            $model->accountManagerId = $seniorAmsIds[0];
        }

        $clientsList = $model->clientsDropDownList();

        echo $form->dropDownListRow(
            $model, 'businessId', $clientsList, array( 'empty'=>'All Clients')
        );
    ?>
</div>
<div class="pull-right">
    <?php
        $this->widget(
            'bootstrap.widgets.TbButton',
            array('buttonType' => 'submit', 'label' => 'View Activities')
        );
    ?>
</div>
<div class="clearfix"></div>
<?php
Yii::app()->getClientScript()->registerScript('search', "
    var activeJrId = $('#AmActivity_accountManagerId').val();

    jQuery('#activitiesSearchForm').submit(function(e) {
        e.preventDefault();
        var el = jQuery(this);
        $.fn.yiiGridView.update('activities', {
            data: el.serialize(),
            url: '".$this->createUrl('/am/juniorAmActivity')."'
        });

        //Reload clients list
        var jrId = $('#AmActivity_accountManagerId').val();
        if(jrId != activeJrId) {
            $.getJSON('/am/juniorAmActivity/ajaxGetClients/'+jrId, function(response) {
                $('#AmActivity_businessId').html('<option value=\"\">All Clients</option>');
                $.each(response, function(index, value) {
                    $('#AmActivity_businessId').append('<option value=\"'+value.id+'\">'+value.name+'</option>');
                });
            });
        }
        activeJrId = jrId;
    });


    $('#AmActivity_accountManagerId').change(function() {
        //Reload clients list
        var jrId = $('#AmActivity_accountManagerId').val();
        if(jrId != activeJrId) {
            $.getJSON('/am/juniorAmActivity/ajaxGetClients/'+jrId, function(response) {
                $('#AmActivity_businessId').html('<option value=\"\">All Clients</option>');
                $.each(response, function(index, value) {
                    $('#AmActivity_businessId').append('<option value=\"'+value.id+'\">'+value.name+'</option>');
                });
            });
        }
        activeJrId = jrId;
    });
");

//End form
$this->endWidget();