<?php $this->renderPartial('/layouts/_tabs/employees', array('active'=>'Administrators')); ?>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <?php $this->widget('bootstrap.widgets.TbButton',array(
            'label'=>' Add an Administrator',
            'size'=>'large',
            'type'=>'info',
            'url'=>array('adminManagement/create'),
            'icon'=>'icon3-user',
        )); ?>
        <h2><i class="icon3-user"></i> Find An Administrator</h2>

        <?php
        Yii::app()->clientScript->registerScript('search', "
            $('#Admin_username').keyup(function(){
                var form = $(this).parents('form:first');
                $.fn.yiiGridView.update('administrators', {
                    data: form.serialize()
                });
            });
        ");
        ?>
        <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'method'=>'get',
            'htmlOptions'=>array('class'=>'form-search'),
        )); ?>

        <?php echo $form->textField($model,'username',array('class'=>'input-xxlarge pull-left', 'autocomplete'=>'off', 'placeholder'=>'Enter text for quicksearch...')); ?>
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
                'id' => 'administrators',
                'template'=>'{items}{pager}',
                'itemsCssClass' => 'oview',
                'columns'=>array(
                    array(
                        'name'  => 'fullName',
                        'header' => 'Account Administrators',
                        'value' => '$data->getFullName(", ")'
                    ),
                    'createdAt:date',
                    array(
                        'class'=>'CLinkColumn',
                        'header'=> 'Action',
                        'label' => 'Edit Settings',
                        'urlExpression' => 'Yii::app()->getController()->createUrl("adminManagement/update",array("id"=>$data->id))'
                    ),
                ),
            ));
            ?>
            </div>
        </div>
    </div>
</div>