<?php
    $searchFieldName = 'companyName';
?>
<div class="accordion" id="accordion2">
    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                Filter by Client <span class="circle pull-right"><b class="caret pull-right"></b></span>
            </a>
        </div>
        <div id="collapseTwo" class="accordion-body collapse">
            <div class="acc_head clearfix accordion-inner">
                <?php $form = $this->beginWidget('CActiveForm', array(
                    'id'          => 'client-filter',
                    'action'      => Yii::app()->createUrl($this->route),
                    'method'      => 'get',
                    'htmlOptions' => array('class'=>'form-search'),
                )); ?>

                <?php echo $form->textField($model, $searchFieldName, array('class'=>'input-block-level', 'placeholder'=>'min. 3 characters')); ?>

                <i class="icon3-search"></i>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

<?php

//Default Search script
if(!isset($searchJs)) {
    $searchJs = "
    jQuery('#".CHtml::activeId($model, $searchFieldName)."').keyup(function() {
        var el = jQuery(this);

        if(el.val().length < 3) {
            return false;
        }

        $.fn.yiiListView.update('".$listViewId."', {
            data: el.parents('form:first').serialize()
        });
    });
";
}

Yii::app()->getClientScript()->registerScript('search', $searchJs);
Yii::app()->getClientScript()->registerScript('client-filter', "jQuery('#client-filter').submit(function(){ return false; });");
