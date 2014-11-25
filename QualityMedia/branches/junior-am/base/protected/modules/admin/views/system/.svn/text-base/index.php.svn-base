<?php $this->renderPartial('_menu') ?>
<div id="main-content" style="overflow: auto">
<div class="row-fluid">
    <h3>Reviews</h3>
    <?php $this->widget('bootstrap.widgets.TbGridView', array(
        'id'=>'reviews-grid',
        'dataProvider'=>$model->search(),
        'type'=>'bordered',
        'template'=>'{items}{pager}',
        'htmlOptions'=>array('style'=>'padding-top:0px; margin-left: 0px'),
        'itemsCssClass'=>'oview',
        'columns' => array(
            'id',
            'businessId',
            'reviewId',
            array(
                'name' => 'reviewContent',
                'value' => 'substr($data->reviewContent, 0, 10)."..."'
            ),
            'starRating',
            'reviewDate',
            'userName',
            'userElite',
            array(
                'name'  => 'publicCommentContent',
                'value' => 'substr($data->publicCommentContent, 0, 10)."..."'
            ),
            'publicCommentAuthor',
            'publicCommentDate',
            'flaggedAt',
            'processed',
            'status',
            array(
                'name'  => 'messagesFolder',
                'value' => 'current($data->getMessagesFolders())[$data->messagesFolder]',
            ),
            'latestMessageDate',
            'lastActionAt',
            'createdAt',
            'updatedAt',
        )
    )); ?>
</div>
</div>