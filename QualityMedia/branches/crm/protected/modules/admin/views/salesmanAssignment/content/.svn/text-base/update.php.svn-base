<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id'=>'horizontalForm',
    'type'=>'vertical',
    // 'enableAjaxValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <div class="gran-data">
        <h2><i class="icon3-file-alt"></i> Assign Sales Rep</h2>

        <div class="controls controls-row">
            <?php echo $form->dropDownListRow($model,'salesmanId',$salesmen->dropDownList('fullName'),array('prompt'=>'Select Sales Rep.')); ?>
        </div>

        <p class="line-base"></p>

        <?php $this->widget('bootstrap.widgets.TbButton',array(
            'label'      => 'Assign Sales Rep',
            'buttonType' => 'submit',
            'size'       => 'large',
            'icon'       => 'icon3-globe',
            'htmlOptions'=> array('class'=>'btn-info'),
        )); ?>
    </div>

<?php $this->endWidget(); ?>