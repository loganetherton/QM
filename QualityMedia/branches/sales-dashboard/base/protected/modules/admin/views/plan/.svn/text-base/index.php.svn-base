<?php
    $this->setPageTitle('Plans');
    $this->renderPartial('/layouts/_tabs/billing', array('active'=>'Plans'));

    $customFormParams = array(
        'type' => 'raw',
        'header' => 'Action',
        'value' => '"Get Custom Form"',
    );

    if($this->salesman->id) {
        $customFormParams = array(
            'class'  => 'CLinkColumn',
            'label'  => 'Get Custom Form',
            'header' => 'Action',
            'urlExpression' => array($this, 'getCustomFormLink'),
            'linkHtmlOptions' => array('target'=>'_blank'),
        );
    }
?>

<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <h2><i class="icon3-inbox"></i> To activate the Custom Form link, please select a Sales Rep</h2>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'method'=>'get',
            'htmlOptions'=>array('class'=>'form-search'),
        )); ?>
        <?php echo $form->dropDownList($this->salesman,'id',$salesmen->dropDownList('fullName'),array('label'=>'ad', 'prompt'=>'Select Sales Rep.')); ?>
        <br />
        <?php
        Yii::app()->clientScript->registerScript('search', "
            var reloadForm = function(formObj) {
                var form = formObj.parents('form:first');
                $.fn.yiiGridView.update('plans', {
                    data: form.serialize()
                });
            }

            $(document).ready(function() {
                $('#Plan_planCode').keyup(function(){
                    reloadForm($(this));
                });

                $('#Salesman_id').change(function() {
                    reloadForm($(this));
                });
            });
        ");
        ?>

        <?php echo $form->textField($model,'planCode',array('class'=>'input-xxlarge pull-left', 'autocomplete'=>'off', 'placeholder'=>'Enter text for quicksearch...')); ?>
        <?php $this->endWidget(); ?>

        <div class="row-fluid">
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'dataProvider' =>$model->search(),
                'htmlOptions'  => array('class' => 'span8'),
                'id'           =>  'plans',
                'type'         =>'bordered',
                'template'     =>'{items}{pager}',
                'responsiveTable' => true,
                'itemsCssClass'=>'oview',
                'columns'=>array(
                    'planCode',
                    'name',
                    array(
                        'name'   => 'amount',
                        'value'  => 'Yii::app()->getComponent("format")->formatMoney($data->getAmount())',
                        'header' => 'Recurring'
                    ),
                    array(
                        'name'   => 'setupFee',
                        'value'  => 'Yii::app()->getComponent("format")->formatMoney($data->getSetupFee())',
                    ),
                    $customFormParams
                ),
                )); ?>
        </div>
    </div>
</div>