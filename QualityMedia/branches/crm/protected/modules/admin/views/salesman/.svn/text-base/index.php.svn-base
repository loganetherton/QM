<?php $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Sales Reps')); ?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <?php $this->widget('bootstrap.widgets.TbButton',array(
            'label'=>' Add a New Sales Rep',
            'size'=>'large',
            'type'=>'info',
            'url'=>array('salesman/create'),
            'icon'=>'icon3-user',
        )); ?>
        <h2><i class="icon3-user"></i> Find A Sales Rep</h2>

        <?php
        Yii::app()->clientScript->registerScript('search', "
            $('#Salesman_fullName').keyup(function(){
                var form = $(this).parents('form:first');
                $.fn.yiiGridView.update('salereps', {
                    data: form.serialize()
                });
            });
        ");
        ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'method'=>'get',
            'htmlOptions'=>array('class'=>'form-search'),
        )); ?>

        <?php echo $form->textField($model,'fullName',array('class'=>'input-xxlarge pull-left', 'autocomplete'=>'off', 'placeholder'=>'Enter text for quicksearch...')); ?>
        <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'submit',
                'type'=>'info',
                'icon'=>'edit',
                'label'=>' Search',
                'htmlOptions'=>array('class'=>'search-button pull-left'),
            ));
        ?>

        <?php $this->endWidget(); ?>

        <div class="row-fluid">
            <div class="span8">
            <?php $this->widget('bootstrap.widgets.TbGridView', array(
                'enableSorting' => false,
                'dataProvider'=>$model->search(),
                'type'=>'bordered',
                'id' => 'salereps',
                'template'=>'{items}{pager}',
                'itemsCssClass' => 'oview',
                'columns'=>array(
                    array(
                        'name'  => 'fullName',
                        'header' => 'Sales Reps',
                        'value' => '$data->getFullName(", ")'
                    ),
                    array(
                        'class'=>'CLinkColumn',
                        'header'=>'Custom Forms',
                        'label'=>'Get Custom Form',
                        'urlExpression'=>'array("salesmanPlan/index", "id"=>$data->id)',
                    ),
                    'createdAt',
                    array(
                        'type'=>'raw',
                        'header'=> 'Action',
                        'value' => 'CHtml::Link("View", Yii::app()->getController()->createUrl("salesman/view",array("id"=>$data->id)))." / ".CHtml::Link("Edit", Yii::app()->getController()->createUrl("salesman/update",array("id"=>$data->id)))'
                    ),
                ),
            ));
            ?>
            </div>
        </div>
    </div>
</div>