<?php $this->renderPartial('_menu') ?>
<h3>Reviews</h3>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'phqueue-grid',
    'dataProvider'=>$dataProvider,
    'type'=>'bordered',
    'template'=>'{items}{pager}',
    'htmlOptions'=>array('style'=>'padding-top:0px; margin-left: 0px'),
    'itemsCssClass'=>'oview',
)); ?>