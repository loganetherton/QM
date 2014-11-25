<div class="yelpBusiness row-fluid" id="<?php printf('yelp-template-%s',$key); ?>">

    <div class="span1">
        <?php echo $form->hiddenField($model,sprintf('[%s]bizId',$key),array('class'=>'templateBizId')); ?>
    </div>

    <div class="span1">
        <?php echo $form->checkBox($model,sprintf('[%s]status',$key)); ?>
    </div>

    <div class="span3">
        <?php echo $form->textFieldRow($model,sprintf('[%s]yelpId',$key),array('class'=>'templateYelpId','autocomplete'=>'off','placeholder'=>'Yelp ID')); ?>
    </div>

    <div class="span3">
        <?php echo $form->textFieldRow($model,sprintf('[%s]label',$key),array('class'=>'templateLabel','autocomplete'=>'off','placeholder'=>'Label')); ?>
    </div>

    <div class="span4">&nbsp;</div>
</div>