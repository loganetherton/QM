<div class="accordion" id="accordion2">
    <div class="accordion-group">
        <div class="accordion-heading">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                Filter by Client <b class="caret pull-right"></b>
            </a>
        </div>
        <div id="collapseTwo" class="accordion-body collapse">
            <div class="accordion-inner">

                <?php $form=$this->beginWidget('CActiveForm', array(
                    'action'=>Yii::app()->createUrl($this->route),
                    'method'=>'get',
                    'htmlOptions'=>array('class'=>'form-search'),
                )); ?>

                    <?php echo $form->textField($model,'companyName',array('class'=>'input-block-level', 'placeholder'=>'min. 3 characters')); ?>

                    <i class="icon3-search"></i>
                <?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

<?php Yii::app()->getClientScript()->registerScript('search', "
    jQuery('#AmReview_companyName').keyup(function() {
        var el = jQuery(this);

        if(el.val().length < 3) {
            return false;
        }

        $.fn.yiiListView.update('reviews-list', {
            data: el.parents('form:first').serialize()
        });
    });
");
