<?php $this->setPageTitle('Users'); ?>
<ul class="nav nav-tabs">
    <li class="active"><a href="#"><i class="icon3-eye-open"></i> Overview</a></li>
    <li><a href="#"><i class="icon3-rss"></i> Manage Clients</a></li>
</ul>
<div id="main-content">
    <div class="span12 row-fluid gran-data">
        <div class="well">
            <?php $this->widget('bootstrap.widgets.TbButton',array(
                'label'=>'Unassigned users',
                'size'=>'small',
                'url'=>array('salesmanAssignment/index'),
                'icon'=>'th-list',
            )); ?>
        </div>

        <?php
        $this->widget('bootstrap.widgets.TbGridView', array(
            'dataProvider'=>$model->search(),
            'type'=>'condensed striped bordered',
            'template'=>'{items}{pager}',
            'columns'=>array(
                array(
                    'name'=>'id',
                    'header'=>'#',
                    'htmlOptions'=>array('style'=>'width: 60px')
                ),
                'email',
                'billingInfo.firstName',
                'billingInfo.lastName',
                'billingInfo.companyName',
                'salesman.username',
                'createdAt:datetime',
                array(
                    'class'=>'bootstrap.widgets.TbButtonColumn',
                    'template'=>'{view} {update}',
                    'buttons'=>array(
                        'update'=>array(
                            'url'=>'Yii::app()->getController()->createUrl("salesmanAssignment/update",array("id"=>$data->id))',
                            'options'=>array(
                                'title'=>'Reassign sales rep.',
                            ),
                        ),
                    ),
                ),
            ),
        ));
        ?>
    </div>
</div>