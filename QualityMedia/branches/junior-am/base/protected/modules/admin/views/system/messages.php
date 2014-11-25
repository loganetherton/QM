<?php $this->renderPartial('_menu') ?>
<h3>Reviews</h3>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'messages-grid',
    'dataProvider'=>$model->search(),
    'type'=>'bordered',
    'template'=>'{items}{pager}',
    'htmlOptions'=>array('style'=>'padding-top:0px; margin-left: 0px'),
    'itemsCssClass'=>'oview',
)); ?>