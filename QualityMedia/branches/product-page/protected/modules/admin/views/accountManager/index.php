<?php $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Account Managers')); ?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <?php $this->widget('bootstrap.widgets.TbButton',array(
            'label'=>' Add New Account Manager',
            'size'=>'large',
            'type'=>'info',
            'url'=>array('accountManager/create'),
            'icon'=>'icon3-user',
        )); ?>
        <h2><i class="icon3-user"></i> Find An Account Manager</h2>
        <?php
        Yii::app()->clientScript->registerScript('search', "
            $('#AccountManager_fullName').keyup(function(){
                var form = $(this).parents('form:first');
                $.fn.yiiGridView.update('accountManagers', {
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
            <?php
             $this->widget('bootstrap.widgets.TbGridView', array(
                'enableSorting' => false,
                'dataProvider'=>$model->search(),
                'type'=>'bordered',
                'template'=>'{items}{pager}',
                'id' => 'accountManagers',
                'itemsCssClass' => 'oview',
                'columns'=>array(
                    array(
                        'name'  => 'fullName',
                        'value' => '$data->getFullName(", ")',
                        'header' => 'Account Managers',
                    ),
                    array(
                        'class'=>'CLinkColumn',
                        'header'=>'Custom Forms',
                        'label'=>'Get Custom Form',
                        'urlExpression'=>'array("accountManagerPlan/index", "id"=>$data->id)',
                    ),
                    'createdAt:date',
                    array(
                        'type'=>'raw',
                        'header'=> 'Action',
                        'value' => 'CHtml::link("View", Yii::app()->getController()->createUrl("accountManager/view",array("id"=>$data->id)))." / ".CHtml::link("Edit Settings", Yii::app()->getController()->createUrl("accountManager/update",array("id"=>$data->id)))'
                    ),
                ),
            ));
            ?>
            </div>
        </div>
    </div>
</div>